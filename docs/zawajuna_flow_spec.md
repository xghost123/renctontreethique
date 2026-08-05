# Zawajuna — Profile Creation Wizard & Browsing Flow — Build Specification

**Source:** extracted Vue 3 SPA bundle of zawajuna (zawajuna-app.pages.dev / app.zawajuna.com), files:
- `raw/chunks/CreatePage-XcuPPbhU.js` — wizard container (5-step stepper + CGU overlay + submit)
- `raw/chunks/Step5About.vue_vue_type_script_setup_true_lang-C1tF5-Ae.js` — exports all 5 step components (`Step1Identity`, `Step2Family`, `Step3Appearance`, `Step4Religion`, `Step5About`)
- `raw/chunks/RegisterPage-COZ39jZJ.js` — signup form + post-signup redirect
- `raw/chunks/SearchPage-9MU3OEiy.js` — user-facing members browsing page (filters, cards, pagination)
- `raw/chunks/ProfileDrawer-BdwpDvzX.js` — full-profile drawer opened from a card
- `raw/chunks/ProfilePage-DCQBnhQU.js` — own/public profile view incl. pending-moderation banners
- `raw/chunks/AppLayout-wCSLztGS.js` — app chrome + nav gating by profile status
- `raw/chunks/EditPage-XM841qu5.js` — edit flow (same wizard + `mosque` field)
- `raw/index-BRHp-FEn.js` — main bundle: i18n FR strings, router guard, auth store
- `raw/Questions.json`, `raw/modeinfo_public.json`, `raw/Predefined_Messages.json`

All French labels below are **verbatim** from the i18n dictionaries / JSX in the bundle.

---

## 0. Global design system (used by every screen)

- Background `#FBF7F0` (warm cream), cards white `rounded-2xl` `border-[#E8E4DA]`, hairline `#F0EDE6`.
- Primary green `#1C4532` (buttons, selected states), gold accent `#C8A028` (required asterisks `*`, progress, hints), error red `#DC2626`, muted `#9CA3AF`, text `#374151`.
- Fonts: body `'Plus Jakarta Sans', sans-serif`; headings/logo `'Cormorant Garamond', serif` (light weight, `tracking-widest uppercase`).
- Input style: `rounded-xl border border-[#E5E7EB] bg-white px-4 py-3 text-sm text-[#1C4532] placeholder-[#9CA3AF] focus:outline-none focus:ring-2 focus:ring-[#C8A028]/30 focus:border-[#C8A028]`.
- Error input: `border-red-300 focus:ring-red-200/70 focus:border-red-400`; error text `mt-1 text-xs text-red-600`.
- Section headers: gold vertical bar `w-0.5 h-5 bg-[#C8A028] rounded-full` + `text-[#1C4532] font-semibold text-xs uppercase tracking-widest`.
- Section dividers: `◆` gold diamond between hairline `bg-[#C8A028]/20` lines.
- Buttons: primary `bg-[#1C4532] text-white rounded-xl py-3.5 text-sm font-semibold`, secondary `border border-[#1C4532] text-[#1C4532]`, `active:scale-95` on press.
- "Radio" controls are **pill buttons** (not native radios): selected = `bg-[#1C4532] border-[#1C4532] text-white`, unselected = `bg-white border-[#E5E7EB] text-[#6B7280] hover:border-[#C8A028]`.

---

## 1. PROFILE WIZARD — `/app/profile/create` (CreatePage)

### 1.1 Route & guards
- `meta: { requiresProfile: false, hideNav: true }` — full-screen wizard, no app nav.
- Unauthenticated → redirected to `/auth/login?redirect=...`.
- Authenticated with completed profile → guard sends to `/app` (search) — wizard not re-shown.
- Local draft persisted under key `"profileDraft"` (via `usePersistentState`, `{persist:true}`): `{currentStep, data, savedAt, progress, isComplete, setStepData, nextStep, prevStep, goToStep, markSaved, reset}`.

### 1.2 Wizard chrome (layout, top → bottom)
1. **Sticky header** (`sticky top-0 z-20 bg-[#FBF7F0]/95 backdrop-blur-sm border-b border-[#E8E4DA]`), inner `max-w-lg mx-auto`:
   - Centered title: **"Créer mon profil"** (`profile.createTitle`), gold hairline rules on both sides, small logout button top-right (icon + `profile... auth.logout` = **"Déconnexion"**) → `signOut()` then push `/auth/login`.
2. **Progress bar**: track `h-1 w-full bg-[#E8E4DA] rounded-full`; fill `bg-gradient-to-r from-[#1C4532] to-[#C8A028]` width = `round(currentStep/5*100)%`, `transition-all duration-500 ease-out`.
3. **Step dots row** (`flex justify-between mt-2`): 5 circular buttons (24 px) with label under each. Past steps: gold `bg-[#C8A028]` + ✓; current: green `bg-[#1C4532]` + number; future: grey `bg-[#E8E4DA] text-[#9CA3AF]` + number. **Only steps strictly before the current one are clickable** (`o+1 < currentStep` → jump back).
   - Step labels (`profile.steps.*`): **1 "Identité"** (icon 👤), **2 "Famille"** (👨👩👧), **3 "Apparence"** (✨), **4 "Pratique"** (☾), **5 "À propos"** (💬).
4. **Card** (`bg-white rounded-2xl shadow-sm border border-[#E8E4DA] overflow-hidden`, `max-w-lg mx-auto px-4 py-6 pb-32`):
   - Card header: `profile.stepOf` = **"Étape {current} sur {total}"** (10px gold uppercase) + step title (`Cormorant Garamond` 2xl) + step emoji (3xl, right).
   - Body: step component, wrapped in `<Transition name="slide-left"|"slide-right" mode="out-in">` (forward vs back), keyed by step number.
   - Under card: `✓ profile.draft.savedAt` = **"✓ Brouillon sauvegardé"** (11px grey, shown once `savedAt` set).
5. **Sticky bottom action bar** (`fixed bottom-0 ... bg-[#FBF7F0]/95 backdrop-blur-sm border-t border-[#E8E4DA] px-4 py-3`, `padding-bottom: max(12px, env(safe-area-inset-bottom))`, inner `max-w-lg mx-auto flex gap-3`):
   - Left (visible from step 2): **"← Précédent"** (`common.previous`) — border-style secondary button.
   - Right (primary, flex-1): step < 5 → **"Suivant →"** (`common.next`); step 5 → **"Valider & Continuer →"** (`profile.cgu.readMore`).
   - The Next button is **disabled** (grey `bg-[#DDD9D0] text-[#9CA3AF] cursor-not-allowed`) while `age` is filled and `< 18`.

### 1.3 Validation engine (shared across steps)
- Per-step `validate()` returns `{isValid, errors, firstField}`; on failure it smooth-scrolls to the first invalid field (`[data-field="<key>"]`) and focuses its control. Errors re-evaluated live on every input (`onUpdate:modelValue`).
- Error messages (`profile.errors.*`): required = **"Ce champ est obligatoire"**; ageMin = **"Vous devez avoir au moins 18 ans"**; ageMax = **"Âge invalide"**; submit = **"Une erreur est survenue lors de la soumission"**.
- Required fields are marked with a **gold `*`**; optional fields show grey **"(optionnel)"** (`common.optional`).
- Gender-dependent rules use the signed-up gender (`gender` from auth metadata, `"male"`/`"female"`).
- Numbers (age, boys, girls, practice-religion): input sanitizer clamps to `Math.max(0, parseInt(v)||0)`; empty string allowed.

### 1.4 STEP 1 — "Identité" (Step1Identity) — fields & layout
**Section "Informations fixes"** (`profile.sections.readonly`) — two read-only boxes (`bg-[#F8F6F0] rounded-xl px-4 py-3 border border-[#E8E4DA]`, value right-aligned + grey pill **"Fixe"** = `common.fixed`):
- **"Identifiant"** (`profile.fields.identifier`) — value = auto-generated `identifier` (timestamp + random 0–99, e.g. `172300000000042`). NOT editable.
- **"Email"** — value = signed-up email. NOT editable.

**Section "Contact & localisation"** (`profile.sections.contact`) — fields in order:
1. **"Âge"** (`profile.fields.age`) — REQUIRED `*` — number input, `min=18 max=99`, placeholder `25`, fixed width `w-28`, centered, suffix **"ans"**. Below: hint **"Vous devez avoir 18 ans ou plus pour vous inscrire."** (`profile.fields.ageHint`, 11px gold) OR red error. Also drives `groupeage` bucket: 18–25 → `"18-25"`, 26–35 → `"26-35"`, 36–45 → `"36-45"`, ≥46 → `"46+"`.
2. **"Kounia / Pseudo"** (`profile.fields.kounia`) — OPTIONAL (optionnel) — text input, placeholder **"Ex : Abou Ibrahim, Oum Salma…"**; hint **"Ce nom sera visible par les autres membres."** Pre-filled from auth user_metadata `kounia`/`name` captured at signup.
3. **"Ville"** (`profile.fields.city`) — REQUIRED `*` — text input, placeholder **"Ex : Paris, Lyon, Bruxelles..."**.
4. **"WhatsApp"** (`profile.fields.whatsapp`) — REQUIRED `*` for **men**, OPTIONAL (optionnel) for **women** — composite control:
   - Left: country-code dropdown button (flag from `https://flagcdn.com/20x15/<iso>.png`, code, chevron). 28 codes hardcoded: France +33, Belgique +32, Suisse +41, Royaume-Uni +44, Allemagne +49, Pays-Bas +31, Espagne +34, Italie +39, Canada +1, États-Unis +1, Maroc +212, Algérie +213, Tunisie +216, Libye +218, Égypte +20, Arabie Saoudite +966, Émirats Arabes Unis +971, Qatar +974, Jordanie +962, Yémen +967, Sénégal +221, Mali +223, Burkina Faso +226, Cameroun +237, Nigéria +234, Indonésie +62, Pakistan +92, Turquie +90. (Default France.)
   - Right: `type="tel"` input, placeholder `6 12 34 56 78`. Stored combined as `"+33 612345678"`.
   - Hint: **"Visible uniquement par l'administration"** (`profile.fields.whatsappHint`).
5. **"Nationalité"** (`profile.fields.nationality`) — REQUIRED `*` — text input, placeholder **"Ex : Française, Marocaine..."**.
6. **"Pays de résidence"** (`profile.fields.countryResidence`) — REQUIRED `*` — `<select>` populated from Supabase `Country` table (`.select("name").order("name")`, cached), placeholder option **"— Sélectionnez votre pays —"**, disabled while loading (**"Chargement..."**). Below: small gold note `profile.wali.noCountryMessage` = **"Vous ne trouvez pas un pays ? Contactez-nous !"**.
7. **"Pays d'origine"** (`profile.fields.origine`) — REQUIRED `*` — `<select>` same `Country` source, placeholder **"— Sélectionnez votre pays d'origine —"**.
8. **"Langue principale"** (`profile.fields.spokenLanguage`) — REQUIRED `*` — 2 pill buttons (grid): **"Français"** (`french`, default) / **"Anglais"** (`english`).

### 1.5 STEP 2 — "Famille" (Step2Family) — fields & layout
**Section "Situation matrimoniale"** (`profile.sections.situation`):
1. **"Situation matrimoniale"** (`profile.fields.maritalStatus`) — REQUIRED `*` — pill grid 2 cols, options **depend on gender** (`profile.options.maritalStatus.*`):
   - For a **male** profile: "Célibataire" (`single`), "Marié" (`married`), "Divorcé" (`divorced`), "Veuf" (`widower`).
   - For a **female** profile: "Célibataire" (`single`), "Divorcée" (`divorced`), "Veuve" (`widow`) — no "Marié".
2. **"Envisagez-vous la polygamie ?"** (`profile.fields.polygamy`) — REQUIRED `*` — **women only** — 2 pills: **"Oui"** (`yes`) / **"Non"** (`no`).

**Section "Enfants"** (`profile.sections.children`):
3. **"Nombre de garçons"** (`profile.fields.boys`) + **"Nombre de filles"** (`profile.fields.girls`) — number inputs `min=0 max=20`, 2-col grid, centered, default 0. Not marked required (0 is valid).
4. **Conditional block** (only if `boys+girls > 0`, animated `fade-down`, box `bg-[#FEFCF7] border border-[#E8E4DA] rounded-xl p-4`):
   - **"Avez-vous des enfants à charge ?"** (`profile.fields.dependentChildren`) — REQUIRED `*` — pills **"Oui"** / **"Non"**.
   - **"Précisez leurs âges"** (`profile.fields.childrenDetails`) — REQUIRED `*` — text input, placeholder **"Ex : 3 ans, 7 ans, 12 ans"**.
   - If both counts reset to 0, these two values are cleared automatically.

**Section "Tuteur (Wali)"** (`profile.sections.wali`) — **women only**:
5. **"Avez-vous un tuteur (wali) ?"** (`profile.fields.hasTutor`) — REQUIRED `*` — pills **"Oui"** (`true`) / **"Non"** (`false`).
6. If **Oui** → box (`bg-[#FEFCF7] rounded-xl p-4 border border-[#E8E4DA]`) with:
   - **"Nom du tuteur"** (`tutorname`) — REQUIRED `*` — text, placeholder **"Nom complet du tuteur"**.
   - **"Téléphone du tuteur"** (`tutorphone`) — REQUIRED `*` — same flag-country-code + tel input composite as WhatsApp (same 28 codes), placeholder **"Numéro de téléphone du tuteur"**.
   - **"Affiliation du tuteur"** (`tutoraffiliation`) — REQUIRED `*` — text, placeholder **"Ex : Mosquée, famille, association..."**.
7. If **Non** → gold-tinted info box (`rounded-xl border border-[#C8A028]/40 bg-[#FEFCF0] p-4`, ☾ icon) with `profile.wali.noTutorMessage`: **"Un tuteur est requis pour un mariage islamique. Sans tuteur, nous ne pouvons vous mettre en relation avec un prétendant avec qui vous souhaitez aller plus loin dans les démarches. Discutez-en avec votre prétendant pour trouver une solution. Contactez-nous pour toute question sur le rôle du tuteur dans l'Islam."**

### 1.6 STEP 3 — "Apparence" (Step3Appearance) — fields & layout
**Section "Profession"** (`profile.sections.profession`):
1. **"Votre métier"** (`profile.fields.job`) — REQUIRED `*` — text input, placeholder **"Ex : Infirmière, Ingénieur..."**.

**Section "Apparence physique"** (`profile.sections.physical`):
2. **"Votre taille"** (`profile.fields.tall`) — REQUIRED `*` — text input, placeholder **`Ex : 175 cm, 5'9", 5 ft 9 in…`**; hint **"Nous vous demandons votre taille, pas votre poids"** (`tallHint`).
3. **"Ethnicité"** (`profile.fields.ethnicity`) — REQUIRED `*` — pill grid 2 cols, 7 options (`profile.options.ethnicity.*`): **"Caucasien(ne)"** (`Caucasian`), **"Arabe"** (`Arabic`), **"Berbère"** (`Berber`), **"Asiatique"** (`Asian`), **"Hispanique"** (`Hispanic`), **"Africain(e)"** (`African`), **"Métis(se)"** (`Mixed (métisse)`).
4. **"Morphologie"** (`profile.fields.bodyType`) — REQUIRED `*` — **men only** — pill grid 2 cols, 4 options: **"Normal"** (`normal`), **"Mince"** (`slim`), **"Maigre"** (`skinny`), **"Surpoids"** (`overweight`).

### 1.7 STEP 4 — "Pratique" (Step4Religion) — fields & layout
**Section "Pratique religieuse"** (`profile.sections.religious`):
1. **"Suivez-vous le minhaj salafi ?"** (`profile.fields.salafy`) — REQUIRED `*` — **vertical full-width pill list** (3 options): **"Oui"** (`yes`), **"Non"** (`no`), **"Pas encore décidé(e)"** (`nodecision`).
2. **"Projet Hijra"** (`profile.fields.hijra`) — REQUIRED `*` — pill grid 2 cols (4 options): **"Court terme"** (`shortterm`), **"Long terme"** (`longterm`), **"Déjà dans un pays musulman"** (`muslimcountry`), **"Non planifié"** (`no_plan`).
3. **"Pratique religieuse sérieuse depuis (en années)"** (`profile.fields.practiceReligion`) — REQUIRED `*` — number input `min=0 max=80` placeholder `0`, suffix **"ans"**. (`0` is a valid value — validation accepts `0`.)
4. **"Votre tenue vestimentaire"** (`profile.fields.dressCode`) — REQUIRED `*` — textarea `rows=3`, placeholder **"Décrivez votre manière de vous habiller..."**.
5. **"Savants / imams que vous suivez"** (`profile.fields.scholars`) — REQUIRED `*` — textarea `rows=2`, placeholder **"Ex : Ibn Baz, Al-Uthaymin..."**.

### 1.8 STEP 5 — "À propos" (Step5About) — fields & layout
**Section "Santé"** (`profile.sections.health`):
1. **"Votre santé physique et morale"** (`profile.fields.health`) — REQUIRED `*` — textarea `rows=3`, placeholder **"Décrivez votre état de santé physique et moral..."**.
2. **"Maladie occulte"** (`profile.fields.occult`) — REQUIRED `*` — textarea `rows=2`, placeholder **"Sihr, envoutement, djinn... Mentionnez si applicable."**.

**Section "Présentation"** (`profile.sections.presentation`):
3. **"Qui êtes-vous ?"** (`profile.fields.bio`) — REQUIRED `*` — textarea `rows=5`, placeholder **"Parlez de vous, de votre personnalité, de vos valeurs..."**.
4. **"Ce que vous cherchez chez le/la prétendant(e)"** (`profile.fields.lookingfor`) — REQUIRED `*` — textarea `rows=4`, placeholder **"Décrivez le profil que vous recherchez..."**.
5. **"Vos critères rédhibitoires"** (`profile.fields.prohibitiveCriteria`) — REQUIRED `*` — textarea `rows=3`, placeholder **"Ce qui est absolument rédhibitoire pour vous..."**.

> **No photo upload exists anywhere in zawajuna** (no photos field in wizard, EditPage, schema or drawer). Avatars are always the **first letter(s) of the kounia** on a gender-colored gradient. Replicate accordingly (or add photos as an enhancement for Rencontre Éthique).

### 1.9 Per-step validation matrix (exact)
| Step | Required fields | Conditional rules |
|---|---|---|
| 1 | `city`, `nationality`, `country`, `origine`, `spoken-langage`, `age` | `phone` required **if gender ≠ female**; `age` must be number 18–99 (else `ageMin`/`ageMax`); age<18 disables Next button too |
| 2 | `marital-status` | `polygamy` required **if female**; if `boys+girls>0` then `dependentchildren` + `children_details` required; `has_tutor` required **if female**; if `has_tutor===true` then `tutorname`, `tutorphone`, `tutoraffiliation` required |
| 3 | `job`, `tall`, `ethnicity` | `body-type` required **if male** |
| 4 | `salafy`, `hijra`, `practice-religion` (0 allowed), `dress-code`, `scholars` | — |
| 5 | `health`, `occult`, `bio`, `lookingfor`, `prohibitive-criteria` | — |

### 1.10 CGU overlay (step 5 → "Valider & Continuer")
- Full-screen overlay (`fixed inset-0 z-50 bg-[#FBF7F0] flex flex-col`, slide-up), header with **"← Retour"** (`common.back`), title **"Conditions Générales d'Utilisation"** (`profile.cgu.title`), subtitle **"Lisez attentivement avant de valider votre profil"** (`profile.cgu.subtitle`).
- Body: scrollable CGU document (CguDocument component, `allow-admin-editing=false`, `show-contact=false`).
- Two checkboxes (custom square, green when checked):
  1. **"J'ai lu et j'accepte les Conditions Générales d'Utilisation"** (`profile.cgu.accept`) → `_cguAccepted`
  2. **"J'atteste devant Allah que les informations transmises sont sincères et exactes"** (`profile.cgu.attest`) → `_cguAttest`
- Submit button **"Soumettre mon profil"** (`profile.cgu.submitBtn`) — disabled until BOTH checked; shows **"Chargement..."** while submitting.

### 1.11 Submit payload (POST `https://oltdfasivytjyvrmgail.supabase.co/rest/v1/Profil`, `Prefer: return=minimal`)
```js
{
  user_id, identifier, name: kounia || identifier, email, gender,
  profilstatus: "new",            // "updated" when editing an already-validated profile
  is_terms_accepted: true, is_available: true, is_subscribed: false,
  groupeage: "18-25"|"26-35"|"36-45"|"46+", age: Number(age),
  city, phone: phone||null, nationality, country, origine, "spoken-langage",
  "marital-status", boys: Number||0, girls: Number||0,
  dependentchildren: ||"no", children_details: ||null, polygamy,
  has_tutor, tutorname:||null, tutorphone:||null, tutoraffiliation:||null,
  job:||null, tall:||null, ethnicity:||null, "body-type":||"",
  salafy:||null, hijra:||null, "practice-religion": Number||0,
  "dress-code":||"", scholars:||"", health:||"", occult:||"",
  bio, lookingfor, "prohibitive-criteria":||null
}
```
**After successful insert:**
1. Upsert `Subscription_paypal` row: `.update({user_id, email}).eq("payerEmail", email)`.
2. Fire-and-forget email via edge fn `mailrelay_sendemail` — subject **"[Zawajuna] - Votre profil est en cours de validation"**, body: *"As salam aleykoum, Nous vous informons que votre profil a bien été soumis et qu'il est actuellement en cours de validation par notre équipe. Vous pouvez accéder à l'application ici : https://app.zawajuna.com. Nous vous contacterons dès que votre profil aura été approuvé. Cordialement, L'équipe Zawajuna"*.
3. `profileDraft.reset()`, reload appUser, **redirect to `profile-view` with `id:"me"`** (own profile, which now shows the pending banner — see §3).
4. On error: red box under the form with `profile.errors.submit` + server message.

---

## 2. POST-SIGNUP FLOW — `/auth/register` (RegisterPage)

### 2.1 Registration form
- Header: **"Créer un compte"** / subtitle **"Rejoignez la communauté Zawajuna"**.
- Fields (in order, Formik-style schema):
  1. **"Langue"** (`auth.language`) — select: **"🇫🇷 Français"** (`fr`, default) / **"🇬🇧 English"** (`en`).
  2. **"Genre"** (`auth.gender`) — select: **"Homme"** (`male`, default) / **"Femme"** (`female`).
  3. **"Kounia / Surnom"** (`auth.kounia`) — text, `autocomplete="nickname"`, placeholder **"Ex : Abou Ibrahim"**. Validation: required, min 2, max 50 chars.
  4. **"Adresse email"** — with a 500 ms debounced **email-suggestion** helper (suggests a corrected domain, clickable "use suggestion").
  5. **"Mot de passe"** — show/hide toggle; min 8 chars.
- Error strings: `auth.errors.*` — **"L'email est requis"**, **"Le mot de passe est requis"**, **"Le mot de passe doit contenir au moins 8 caractères"**, **"Les mots de passe ne correspondent pas"**, **"La kounia est requise"**, **"La kounia doit contenir au moins 2 caractères"**, **"La kounia ne peut pas dépasser 50 caractères"**.
- Links: **"Pas encore de compte ?"** / **"Déjà un compte ?"** → login; **"Mot de passe oublié ?"**.

### 2.2 On submit
1. `supabase.auth.signUp(email, password, { data: { language, gender, kounia } })` (kounia + gender stored in user_metadata — used later to prefill wizard).
2. Fire-and-forget `add_mailrelay_audience?UserEmail=<email>` (newsletter list).
3. **If `session` returned (email confirmation disabled):** redirect `/app` → router guard sees `hasCompletedProfile=false` → **immediately lands on `/app/profile/create`** (the wizard).
4. **If no session (email confirmation enabled):** full-screen success state: checkmark SVG, **"Email envoyé !"**, **"Consultez votre boîte mail pour confirmer votre adresse, puis connectez-vous."**, button **"Aller à la connexion"**.
5. Login page (`/auth/login`): email+password, **"Se connecter"**; on success → moderator/admin ⇒ `/admin` (moderation messages), `hasCompletedProfile` ⇒ `/app` (search), else ⇒ `/app/profile/create`. Wrong creds: **"Email ou mot de passe incorrect"**.

### 2.3 Draft / resume behavior
- Wizard writes every step into `localStorage("profileDraft")` (`usePersistentState`). Leaving mid-way keeps `currentStep`, filled data, and `savedAt`; returning restores the step and shows **"✓ Brouillon sauvegardé"**.
- A user with `profilstatus === "draft"` (or no profile row) is always redirected to the wizard by the guard (`!hasCompletedProfile → profile-create`).
- Identifier is generated once per draft and kept (`identifier` merged from `p.data`).

---

## 3. APPROVAL / PENDING STATE (profilstatus = `"new"`)

### 3.1 Router guard (global beforeEach)
```
role in (moderator, supervisor, admin) → default route = admin-moderation-messages
else default = search
guestOnly + authed → default route
requiresAuth && !user → /auth/login?redirect=...
requiresProfile !== false && authed && !moderator && profileLoaded:
    !hasCompletedProfile            → redirect /app/profile/create
    appUser.salafy missing          → redirect /app/profile/edit   (legacy completion)
pending = profilstatus in ("new","rejected")
if authed && !moderator && pending:
    blocked routes = search, conversations, chat, subscription, payment
    blocked = profile-view with id != "me"
    → redirect { name: "profile-view", params: { id: "me" } }
```
⇒ **While waiting for approval the member is locked to their own profile page.** No browsing, no messaging, no payments.

### 3.2 App chrome (AppLayout) — nav gating
- `pending = profilstatus in ("new","draft","rejected")` → full nav list filtered to: **"Mon profil"**, **"CGU"**, **"Nous contacter"**; **"Recherche"**, **"Conversations"**, **"Mon abonnement"** are removed from the drawer AND the topbar search/conversations icon buttons are hidden.
- Normal nav: **Mon profil** (profile-view/me), **Recherche** (search), **Conversations** (with unread badge `9+`), **Mon abonnement** (subscription), **CGU**, **Nous contacter**.
- Topbar: hamburger, logo **زواجنا / Zawajuna**, pull-to-refresh (gold star indicator).

### 3.3 Own profile page while pending (`/app/profile/:id` with id=`me`)
- Header hero (green gradient, decorative gold pattern): initial-letter avatar (first letters of name, max 2, e.g. "AB"), gender dot, **name**, chips **"{age} ans"** + **"{city}, {country}"**, status badge, plan badge (**"Payant"** / **"Gratuit"**).
- **Status badge** (`status-badge`, colored by status) — full status map (`profile.status.*`):
  - `draft` → **"Brouillon"** (grey #64748B / #F1F5F9)
  - `new` → **"En attente de validation"** (gold #C8A028 / #FFFBEB)
  - `updated` → **"En cours de modération"** (gold)
  - `validated` → **"Profil validé"** (green #1C4532 / #F0FDF4)
  - `rejected` → **"Profil refusé"** (red #DC2626 / #FEF2F2)
  - `suspended` → **"Profil suspendu"** (orange #EA580C / #FFF7ED)
  - `closed` → **"Profil fermé"** (grey)
  - active/validated + reject_reason → **"Modifications refusées"** (orange)
- **Moderation card** (status `new`), gold-tinted panel with clock icon:
  - Title: **"Profil en cours de validation"**
  - Body: **"Votre profil a bien été créé et soumis à notre équipe. Il sera examiné dans les meilleurs délais. Vous recevrez un message dès qu'il sera validé."**
- **Moderation card** (status `updated`): title **"Profil en cours de modération"**, body **"Vos modifications ont bien été envoyées. Notre équipe les examinera dans les meilleurs délais. Vous recevrez un message dès que votre profil sera validé."**
- **Rejected banner** (red, `alert-red`): title **"Motif de refus"** + `reject_reason` text.
- **Suspended banner** (orange): title **"Motif de suspension"** + `suspend_reason`.
- **"Modifications refusées" banner** (orange): **"Motif : {reject_reason}. Votre profil précédent reste actif et visible."**
- Actions on own profile: **Modifier** (→ edit wizard) + **Se déconnecter**. Suspended profiles cannot open the edit page (redirect back to profile).
- Moderation hours (public `modeinfo_public`): **"Du mardi à dimanche : 9h à 22h — Vendredi : 15h30 à 22h — Lundi : fermé"**; moderation contact `contact.zawajuna@gmail.com`.
- **Approval email** (from moderator side, template `[Zawajuna]`): profile approved → user is notified by email; status flips to `validated`/`active`; on next load the nav unlock happens automatically (guard reads `profilstatus`).

---

## 4. BROWSING PAGE — `/app` (SearchPage) — members of the opposite gender

### 4.1 Query model (all filters live in the URL query string → shareable/bookmarkable)
Source view: **`profil_userview`** (`select("*", {count:"exact"})`). Pagination `K=20`/page, `.order("created_at", {ascending:false})`, `.range((page-1)*20, page*20-1)`.

**Fixed (implicit) filters:**
- `gender` = **opposite of viewer** (male viewer ⇒ `female`, female viewer ⇒ `male`).
- `profilstatus in ("active","updated")` — only approved profiles.

**User filters (all optional, multi-selectable):**
| Filter | UI control | French label (verbatim) | Query |
|---|---|---|---|
| Identifiant | text input (debounced 300 ms, `ilike %…%`) | **"Identifiant"** | `ilike("identifier", %v%)` |
| Tranche d'âge | pill chips 4 options | **"Tranche d'âge"** — `18-25`, `26-35`, `36-45`, `46+` | `or(and(age.gte.18,age.lte.25),…)` |
| Pays de résidence | multi-select dropdown w/ search | **"Pays de résidence"** (placeholder **"Tous les pays"**, search **"Rechercher un pays…"**, selected chip count `"{n} sélectionné(s)"`) | `in("country", […])` |
| Pays d'origine | multi-select dropdown w/ search | **"Pays d'origine"** (placeholder **"Toutes les origines"**, search **"Rechercher une origine…"**) | `in("origine", […])` |
| Statut matrimonial | pill chips, gender-dependent | **"Statut matrimonial"** — female viewer sees: **"Célibataire"**, **"Divorcé"**, **"Veuf"**, **"Marié"** (male targets); male viewer sees: **"Célibataire"**, **"Divorcée"**, **"Veuve"** | `in("marital-status", […])` |
| Sans enfant | toggle switch | **"Sans enfant"** | `eq(boys,0).eq(girls,0)` |
| Salafi uniquement | toggle switch + sub-label | **"Salafi uniquement"** / **"Profils se déclarant salafi"** | `neq("salafy","no")` |
| Polygamie (men only) | toggle switch + sub-label | **"Polygamie"** / **"Oui ou en réflexion"** | `in("polygamy",["yes","nodecision"])` |

- **Active-filter count badge** on the "Filtres" button (count of non-empty filter groups).
- Panel footer button: **"Voir {n} résultat(s)"** / **"Aucun profil ne correspond"** / **"Recherche…"**; **"Réinitialiser"** clears all (also `Effacer les filtres` label in i18n).
- Mobile: filters in a bottom-sheet (backdrop blur) with slide-up transition; desktop: side panel (`aside`).
- Results header: **"Recherche"** title + **"Chargement…"** while loading; tab pills **" Recherche "** and **" Épinglés "** (pinned count badge).

### 4.2 Pinned ("Épinglés") tab
- Heart/pin store (`likes` table) — pin/unpin from drawer. "Épinglés" tab queries `profil_userview .in("identifier", likedIdentifiers) .in("profilstatus", ["active","updated"])`.
- Empty state: **"Aucun profil épinglé"** / **"Ouvrez un profil et appuyez sur « Épingler » pour le retrouver ici"** + button **"Explorer les profils"**.

### 4.3 Member card (grid; click → ProfileDrawer)
- **Header banner 110 px**: gender-coded gradient — male `linear-gradient(145deg,#0F2D20 0%,#1C4532 55%,#153824 100%)`; female `linear-gradient(145deg,#2E0D1A 0%,#6B1F38 55%,#3D1225 100%)`; overlaid gold hexagonal-geometry SVG pattern (`#C8A028` strokes), 2 px gold top edge line.
- **Initial-letter avatar** (first char of `name||identifier`, uppercase) in a circle on the banner.
- **Badges on banner**: gold star if pinned (**"Épinglé"**); **"Consulté"** chip (eye icon) if already viewed; pulsing-gold-dot chip **"En échange"** when `is_available === false` (profile currently in an active exchange).
- **Age chip**: **"{age} ans"** (top-right).
- **Body** (white card): **name** (kounia) → 28 px gold gradient divider → lines **"📍 {city}, {country}"** and **"🌍 {origine}"** → tag row:
  - marital status (gender-corrected: `Divorcé`/`Divorcée`/`Veuve`/`Veuf`/`Marié`/`Célibataire`)
  - **"Sans enfant"** if boys=0 & girls=0
  - **"👦 {n} garçon(s)"**, **"👧 {n} fille(s)"** when >0
  - female only: **"Polygamie ✓"** (yes) / **"Polygamie : en réflexion"** (nodecision)
- Hover: inset ring + colored shadow (`box-shadow: inset 0 0 0 1.5px …`); click → mark viewed + open drawer.
- Loading: 6 pulse skeleton cards (gradient bar + grey lines). Empty state: **"Aucun profil trouvé"** / **"Essayez de modifier ou réinitialiser vos filtres"** + **"Réinitialiser les filtres"**.
- Pagination: prev/next round buttons + numbered pills (smart ellipsis, max 7 slots, current = green).

### 4.4 ProfileDrawer (full member profile, slide-in from right)
- **Hero** (same gender gradient + decorative circles/polygon + shimmer): avatar initial, name, chips **"{age} ans"** + **"{city}, {country}"**, clickable identifier row with copy button ("Copié !").
- Actions: **"Épingler"** / **"Épinglé"**, **"Bloquer"** / **"Débloquer"**.
- **Sections (label — value):**
  - **Présentation** — `bio`
  - **Identité** — **Situation** (marital, gender-corrected), **Origine**, **Nationalité**, **Pays de résidence**, **Profession**, **Langues**
  - **Famille** — **Enfants** (="Sans enfant"), **Garçons** 👦, **Filles** 👧, **Polygamie** (Oui/Non)
  - **Pratique religieuse** — **Pratique depuis** "{n} ans", **Minhaj salafi** (value or italic **"Non renseigné"**), **Projet Hijra** (Court terme/Long terme/Déjà dans un pays musulman/Non planifié), **Mosquée** (field exists in schema/EditPage), **Savants / imams suivis**
  - **Santé** — **Santé physique et morale**, **Maladie occulte**
  - **Apparence** — **Taille**, **Corpulence**, **Tenue**
  - **Projet de vie** — **Je recherche**, **Critères rédhibitoires**
- **CTA (sticky bottom):** **"Inviter à échanger"** (disabled → **"Déjà en échange"** when `is_available=false`, or **"Vous êtes déjà en échange"** when an exchange exists). Requires active subscription → else modal **"Abonnement requis"** ("Pour envoyer une invitation à échanger, vous devez disposer d'un abonnement actif. Souscrivez dès maintenant pour accéder à toutes les fonctionnalités." + **"Voir les offres"**).
- **Invite confirmation modal:** **"Envoyer une invitation ?"** — "Vous allez envoyer une demande d'échange à **{name}**. Le profil pourra accepter ou refuser votre invitation." → creates `Conversation {owner_id: me, dest_id: target, owner_name, dest_name, status: "waiting"}` + email "[Zawajuna] : {name} souhaite échanger avec vous". One active exchange at a time (**"Un seul échange à la fois"** modal). Blocked/suspended/banned targets blocked with explanations.

### 4.5 Moderation screening (context for the approval step)
- Public `Questions` table = 40 gender-tagged screening questions moderators ask during validation (e.g. *"Es-tu prête à suivre ton mari, peu importe l'endroit, même si c'est loin de tes parents ?"*, *"Quel est ton niveau de dîne et d'arabe ?"*, *"Que penses-tu des gouverneurs ?"*). Not part of the user wizard — informational.
- Moderator review happens via `/admin/moderation/profiles` (ProfilesPage) using canned messages (`Predefined_Messages`, e.g. wali reminders) and `update_profile_status` edge fn.

---

## 5. EDIT FLOW — `/app/profile/edit` (EditPage)
- Identical 5-step wizard (same components), pre-filled from `Profil` row (incl. `name → kounia`), plus a **"Mosquée fréquentée"** field (`mosque`, placeholder **"Ex : Mosquée de Paris..."**) appended in the religion step's data model.
- Submit: `profilstatus = "new"` if current is `new`/`rejected`, else `"updated"`; `reject_reason: null`; same mailrelay notification flow; banner **"Profil en cours de modération"** shown afterwards.
- Notice text: **"Vos modifications seront soumises à modération avant publication."** (`profile.editNotice`).

---

## 6. DATA MODEL (Profil table columns used by these flows)
`user_id, identifier, email, gender, name, kounia(→name), age, groupeage, city, phone, nationality, country, origine, spoken-langage, marital-status, boys, girls, dependentchildren, children_details, polygamy, has_tutor, tutorname, tutorphone, tutoraffiliation, job, tall, ethnicity, body-type, salafy, hijra, practice-religion, dress-code, scholars, mosque, health, occult, bio, lookingfor, prohibitive-criteria, profilstatus, is_available, is_subscribed, is_terms_accepted, reject_reason, suspend_reason, cancelation_reason, plan, Validated_by, message_count, visio_done, marked_at, marked_by, created_at`

Status lifecycle: `draft → new → (validated/active) → updated (re-moderation) | rejected | suspended | banned | closed`.

---

## 7. ADAPTATION NOTES for Rencontre Éthique (mosque-based halal marriage app)
1. **No "madhab" field exists in zawajuna** (verified: grep over all 77 chunks). The religious-practice axis is `salafy` (Oui/Non/Pas encore décidé(e)) + `scholars` + `hijra` + `practice-religion` years + `dress-code`. If Rencontre Éthique wants madhab (Hanafi/Maliki/Chafi'i/Hanbali), add it as a new pill-radio in Step 4 alongside `salafy`, and add a matching browse filter.
2. **Mosque membership is implicit in zawajuna** (single `mosque` text field only in edit flow). For Rencontre Éthique, make `mosque` (or a mosque_id FK) part of Step 1 or registration, and add it to the implicit browse filter (like `gender`) so members only see same-mosque opposite-gender profiles.
3. **Gender-based branching is the core mechanic**: replicate Step 2 (polygamy + wali for women) and Step 3 (body-type for men) conditionals exactly; wali info is required for women and displayed in the drawer.
4. **Keep the pending-state lock**: profilstatus `new`/`rejected` ⇒ hide search/conversations/subscription nav + router-redirect to own profile with the "Profil en cours de validation" card. This is the strongest differentiator vs. open dating apps.
5. **Keep filters minimal & URL-synced**: identifier, age ranges, country, origine, marital status, sans enfant, salafi, polygamie — plus (for RE) mosque and optionally madhab; 20/page grid; "Épinglés" favorites tab.
6. **No photo upload** — initial-letter avatars only; consider adding photo upload behind moderation if desired.
7. Labels: reuse the verbatim French strings above for exact parity.
