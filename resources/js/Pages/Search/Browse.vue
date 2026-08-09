<template>
  <div class="browse-profiles-container">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">Étape 3: Recherche</h1>
        <p class="hero-subtitle">Découvrez les profils anonymes de votre communauté</p>
        <div class="hero-note">
          <i class="icon-info"></i>
          <span>Tous les profils restent anonymes durant le processus</span>
        </div>
      </div>
      <!-- Islamic Pattern Background -->
      <div class="islamic-pattern"></div>
    </section>

    <div class="browse-wrapper">
      <!-- Filter Section -->
      <aside class="filter-sidebar">
        <div class="filter-header">
          <h2>Filtres</h2>
          <button v-if="filtersActive" class="reset-btn" @click="resetFilters">
            Réinitialiser
          </button>
        </div>

        <!-- Gender Filter -->
        <div class="filter-group">
          <label class="filter-label">Genre</label>
          <div class="filter-options">
            <label class="radio-option">
              <input
                v-model="filters.gender"
                type="radio"
                value="femme"
              />
              <span>Femme</span>
            </label>
            <label class="radio-option">
              <input
                v-model="filters.gender"
                type="radio"
                value="homme"
              />
              <span>Homme</span>
            </label>
            <label class="radio-option">
              <input
                v-model="filters.gender"
                type="radio"
                value=""
              />
              <span>Tous</span>
            </label>
          </div>
        </div>

        <!-- Age Range Slider -->
        <div class="filter-group">
          <label class="filter-label">Âge</label>
          <div class="age-inputs">
            <input
              v-model.number="filters.ageMin"
              type="number"
              min="18"
              max="80"
              placeholder="Min"
              class="age-input"
            />
            <span class="age-dash">-</span>
            <input
              v-model.number="filters.ageMax"
              type="number"
              min="18"
              max="80"
              placeholder="Max"
              class="age-input"
            />
          </div>
          <input
            v-model.number="filters.ageMin"
            type="range"
            min="18"
            max="80"
            class="range-slider"
          />
          <input
            v-model.number="filters.ageMax"
            type="range"
            min="18"
            max="80"
            class="range-slider"
          />
        </div>

        <!-- Mosque/Location Filter -->
        <div class="filter-group">
          <label class="filter-label">Mosquée</label>
          <select v-model="filters.mosque" class="filter-select">
            <option value="">Toutes les mosquées</option>
            <option value="mosque-1">Mosquée Centrale - Paris</option>
            <option value="mosque-2">Mosquée Al-Noor - Lyon</option>
            <option value="mosque-3">Mosquée Assalam - Marseille</option>
            <option value="mosque-4">Mosquée Istiqlal - Toulouse</option>
            <option value="mosque-5">Mosquée Al-Qasba - Nice</option>
          </select>
        </div>

        <!-- Education Filter -->
        <div class="filter-group">
          <label class="filter-label">Éducation</label>
          <div class="filter-checkboxes">
            <label class="checkbox-option">
              <input
                v-model="filters.education"
                type="checkbox"
                value="bac"
              />
              <span>Baccalauréat</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.education"
                type="checkbox"
                value="licence"
              />
              <span>Licence</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.education"
                type="checkbox"
                value="master"
              />
              <span>Master</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.education"
                type="checkbox"
                value="doctorat"
              />
              <span>Doctorat</span>
            </label>
          </div>
        </div>

        <!-- Personality Traits Filter -->
        <div class="filter-group">
          <label class="filter-label">Traits de personnalité</label>
          <div class="filter-checkboxes">
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="kind"
              />
              <span>Bienveillant</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="ambitious"
              />
              <span>Ambitieux</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="family-oriented"
              />
              <span>Familial</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="intellectual"
              />
              <span>Intellectuel</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="spiritual"
              />
              <span>Spirituel</span>
            </label>
            <label class="checkbox-option">
              <input
                v-model="filters.traits"
                type="checkbox"
                value="creative"
              />
              <span>Créatif</span>
            </label>
          </div>
        </div>

        <!-- Apply Filters Button -->
        <button class="apply-filters-btn" @click="applyFilters">
          <span>Appliquer les filtres</span>
          <i class="icon-check"></i>
        </button>
      </aside>

      <!-- Main Content -->
      <main class="profiles-main">
        <!-- Results Header -->
        <div class="results-header">
          <h2 class="results-title">Profils disponibles</h2>
          <p class="results-count">
            Affichage {{ displayedCount }} de {{ totalCount }} profils
          </p>
        </div>

        <!-- Empty State -->
        <div v-if="filteredProfiles.length === 0" class="empty-state">
          <div class="empty-icon">
            <i class="icon-search"></i>
          </div>
          <h3>Aucun profil trouvé</h3>
          <p>Essayez d'ajuster vos filtres pour voir plus de résultats</p>
          <button class="empty-reset-btn" @click="resetFilters">
            Réinitialiser les filtres
          </button>
        </div>

        <!-- Profiles Grid -->
        <div v-else class="profiles-grid">
          <div
            v-for="profile in paginatedProfiles"
            :key="profile.id"
            class="profile-card"
            @click="selectedProfile = profile"
          >
            <!-- Card Header with Avatar -->
            <div class="card-header">
              <div class="avatar-container">
                <div class="anonymous-avatar">
                  <i class="icon-mosque"></i>
                </div>
                <span class="anonymous-badge">Anonyme</span>
              </div>
            </div>

            <!-- Card Content -->
            <div class="card-content">
              <div class="profile-info">
                <h3 class="profile-age">{{ profile.age }} ans</h3>
                <p class="profile-location">
                  <i class="icon-location"></i>
                  {{ profile.mosque }}
                </p>
                <p class="profile-education">
                  <i class="icon-graduation"></i>
                  {{ profile.education }}
                </p>
              </div>

              <p class="profile-bio">{{ truncateBio(profile.bio) }}</p>

              <div class="card-actions">
                <button
                  class="exchange-btn"
                  @click.stop="openExchangeModal(profile)"
                >
                  <i class="icon-heart"></i>
                  Demander un Échange
                </button>
                <button
                  class="more-btn"
                  @click.stop="openProfileDetail(profile)"
                >
                  <i class="icon-arrow"></i>
                  Voir Plus
                </button>
              </div>
            </div>

            <!-- Hover Overlay -->
            <div class="card-overlay">
              <div class="overlay-content">
                <p class="overlay-text">
                  Découvrez ce profil intéressant
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="filteredProfiles.length > itemsPerPage" class="pagination-container">
          <button
            :disabled="currentPage === 1"
            class="pagination-btn"
            @click="previousPage"
          >
            <i class="icon-chevron-left"></i>
            Précédent
          </button>

          <div class="pagination-info">
            Page {{ currentPage }} sur {{ totalPages }}
          </div>

          <button
            :disabled="currentPage === totalPages"
            class="pagination-btn"
            @click="nextPage"
          >
            Suivant
            <i class="icon-chevron-right"></i>
          </button>
        </div>
      </main>
    </div>

    <!-- Profile Detail Modal -->
    <transition name="modal">
      <div v-if="selectedProfile && !exchangeModalOpen" class="modal-overlay" @click="closeModal">
        <div class="modal-content" @click.stop>
          <button class="modal-close" @click="closeModal">
            <i class="icon-close"></i>
          </button>

          <div class="modal-header">
            <div class="modal-avatar">
              <i class="icon-mosque"></i>
            </div>
            <div class="modal-badges">
              <span class="badge anonymous-badge">Anonyme</span>
            </div>
          </div>

          <div class="modal-body">
            <div class="modal-section">
              <h3 class="modal-section-title">Informations personnelles</h3>
              <div class="info-grid">
                <div class="info-item">
                  <label>Âge</label>
                  <p>{{ selectedProfile.age }} ans</p>
                </div>
                <div class="info-item">
                  <label>Mosquée</label>
                  <p>{{ selectedProfile.mosque }}</p>
                </div>
                <div class="info-item">
                  <label>Éducation</label>
                  <p>{{ selectedProfile.education }}</p>
                </div>
              </div>
            </div>

            <div class="modal-section">
              <h3 class="modal-section-title">À propos</h3>
              <p class="modal-bio">{{ selectedProfile.bio }}</p>
            </div>

            <div class="modal-section">
              <h3 class="modal-section-title">Traits de personnalité</h3>
              <div class="traits-list">
                <span
                  v-for="trait in selectedProfile.traits"
                  :key="trait"
                  class="trait-badge"
                >
                  {{ formatTrait(trait) }}
                </span>
              </div>
            </div>

            <div class="modal-section">
              <h3 class="modal-section-title">Objectifs</h3>
              <p>{{ selectedProfile.goals }}</p>
            </div>

            <!-- Similar Matches -->
            <div class="modal-section">
              <h3 class="modal-section-title">Profils similaires</h3>
              <div class="similar-profiles">
                <div
                  v-for="similar in selectedProfile.similarMatches"
                  :key="similar.id"
                  class="similar-card"
                >
                  <div class="similar-avatar">
                    <i class="icon-mosque"></i>
                  </div>
                  <div class="similar-info">
                    <p class="similar-age">{{ similar.age }} ans</p>
                    <p class="similar-location">{{ similar.mosque }}</p>
                  </div>
                  <div class="similar-match-score">
                    {{ similar.matchScore }}%
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button
              class="exchange-btn-large"
              @click="openExchangeModal(selectedProfile)"
            >
              <i class="icon-heart"></i>
              Demander un Échange
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Exchange Request Modal -->
    <transition name="modal">
      <div v-if="exchangeModalOpen" class="modal-overlay" @click="closeExchangeModal">
        <div class="modal-content modal-exchange" @click.stop>
          <button class="modal-close" @click="closeExchangeModal">
            <i class="icon-close"></i>
          </button>

          <div class="exchange-header">
            <i class="icon-heart"></i>
            <h2>Envoyer une Demande d'Échange?</h2>
          </div>

          <div class="exchange-body">
            <p class="exchange-subtitle">
              Vous allez envoyer une demande d'échange à ce profil
            </p>

            <div class="message-field">
              <label for="exchange-message">Message optionnel</label>
              <textarea
                id="exchange-message"
                v-model="exchangeMessage"
                placeholder="Partagez un peu plus sur vous... (optionnel)"
                rows="5"
              ></textarea>
              <p class="message-hint">
                {{ exchangeMessage.length }}/500 caractères
              </p>
            </div>
          </div>

          <div class="exchange-footer">
            <button class="btn-cancel" @click="closeExchangeModal">
              Annuler
            </button>
            <button class="btn-confirm" @click="submitExchange" :disabled="isSubmitting">
              <span v-if="!isSubmitting">Confirmer</span>
              <span v-else>Envoi en cours...</span>
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Success Toast -->
    <transition name="toast">
      <div v-if="showSuccess" class="success-toast">
        <i class="icon-check-circle"></i>
        <span>Demande d'échange envoyée avec succès!</span>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'BrowseProfiles',

  data() {
    return {
      filters: {
        gender: '',
        ageMin: 18,
        ageMax: 80,
        mosque: '',
        education: [],
        traits: [],
      },
      filtersActive: false,
      currentPage: 1,
      itemsPerPage: 12,
      selectedProfile: null,
      exchangeModalOpen: false,
      exchangeMessage: '',
      isSubmitting: false,
      showSuccess: false,

      // Mock data - replace with API calls
      allProfiles: [
        {
          id: 1,
          age: 28,
          gender: 'femme',
          mosque: 'Mosquée Centrale - Paris',
          education: 'Master',
          bio: 'Passionnée par les voyages et la lecture, je cherche quelqu\'un de bienveillant et respectueux...',
          traits: ['kind', 'family-oriented', 'intellectual'],
          goals: 'Cherche une relation sérieuse menant au mariage',
          similarMatches: [
            { id: 5, age: 30, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 92 },
            { id: 6, age: 27, mosque: 'Mosquée Assalam - Marseille', matchScore: 88 },
            { id: 7, age: 29, mosque: 'Mosquée Centrale - Paris', matchScore: 85 },
          ],
        },
        {
          id: 2,
          age: 32,
          gender: 'femme',
          mosque: 'Mosquée Al-Noor - Lyon',
          education: 'Licence',
          bio: 'Professionnelle ambitieuse, spirituelle, cherche quelqu\'un de sincère et engagé...',
          traits: ['ambitious', 'spiritual', 'family-oriented'],
          goals: 'Relation sérieuse basée sur le respect mutuel',
          similarMatches: [
            { id: 8, age: 33, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 90 },
            { id: 9, age: 31, mosque: 'Mosquée Centrale - Paris', matchScore: 87 },
            { id: 10, age: 34, mosque: 'Mosquée Al-Qasba - Nice', matchScore: 84 },
          ],
        },
        {
          id: 3,
          age: 25,
          gender: 'femme',
          mosque: 'Mosquée Assalam - Marseille',
          education: 'Baccalauréat',
          bio: 'Jeune femme créative qui aime l\'art et la musique, cherche quelqu\'un d\'authentique...',
          traits: ['creative', 'kind', 'spiritual'],
          goals: 'Trouver l\'âme soeur pour une vie heureuse ensemble',
          similarMatches: [
            { id: 11, age: 26, mosque: 'Mosquée Centrale - Paris', matchScore: 89 },
            { id: 12, age: 24, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 86 },
            { id: 13, age: 27, mosque: 'Mosquée Assalam - Marseille', matchScore: 83 },
          ],
        },
        {
          id: 4,
          age: 30,
          gender: 'femme',
          mosque: 'Mosquée Istiqlal - Toulouse',
          education: 'Master',
          bio: 'Doctorante passionnée par les sciences, je valorise l\'intelligence et l\'humour...',
          traits: ['intellectual', 'ambitious', 'kind'],
          goals: 'Cherche un partenaire qui comprend mon dévouement à mes études',
          similarMatches: [
            { id: 14, age: 32, mosque: 'Mosquée Al-Qasba - Nice', matchScore: 88 },
            { id: 15, age: 29, mosque: 'Mosquée Centrale - Paris', matchScore: 85 },
            { id: 16, age: 31, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 82 },
          ],
        },
        {
          id: 5,
          age: 27,
          gender: 'femme',
          mosque: 'Mosquée Al-Qasba - Nice',
          education: 'Licence',
          bio: 'Enseignante dévouée, je crois au pouvoir de l\'éducation et de la famille...',
          traits: ['family-oriented', 'kind', 'spiritual'],
          goals: 'Cherche quelqu\'un pour construire une belle famille',
          similarMatches: [
            { id: 17, age: 28, mosque: 'Mosquée Centrale - Paris', matchScore: 91 },
            { id: 18, age: 26, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 87 },
            { id: 19, age: 29, mosque: 'Mosquée Assalam - Marseille', matchScore: 84 },
          ],
        },
        {
          id: 6,
          age: 29,
          gender: 'femme',
          mosque: 'Mosquée Centrale - Paris',
          education: 'Master',
          bio: 'Avocate engagée pour la justice sociale, je cherche quelqu\'un de progressiste...',
          traits: ['ambitious', 'intellectual', 'kind'],
          goals: 'Partenaire pour une vie de collaboration et de respect',
          similarMatches: [
            { id: 20, age: 30, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 89 },
            { id: 21, age: 28, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 86 },
            { id: 22, age: 31, mosque: 'Mosquée Al-Qasba - Nice', matchScore: 83 },
          ],
        },
        {
          id: 7,
          age: 26,
          gender: 'femme',
          mosque: 'Mosquée Al-Noor - Lyon',
          education: 'Baccalauréat',
          bio: 'Styliste passionnée, j\'aime la beauté sous toutes ses formes et l\'art...',
          traits: ['creative', 'spiritual', 'family-oriented'],
          goals: 'Cherche un futur mari et un père de famille aimant',
          similarMatches: [
            { id: 23, age: 27, mosque: 'Mosquée Assalam - Marseille', matchScore: 90 },
            { id: 24, age: 25, mosque: 'Mosquée Centrale - Paris', matchScore: 87 },
            { id: 25, age: 28, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 84 },
          ],
        },
        {
          id: 8,
          age: 31,
          gender: 'femme',
          mosque: 'Mosquée Assalam - Marseille',
          education: 'Doctorat',
          bio: 'Chercheuse en biologie, je suis passionnée par mon travail et la nature...',
          traits: ['intellectual', 'spiritual', 'ambitious'],
          goals: 'Trouver quelqu\'un qui partage mes passions et mes valeurs',
          similarMatches: [
            { id: 26, age: 32, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 92 },
            { id: 27, age: 30, mosque: 'Mosquée Centrale - Paris', matchScore: 88 },
            { id: 28, age: 33, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 85 },
          ],
        },
        {
          id: 9,
          age: 24,
          gender: 'femme',
          mosque: 'Mosquée Istiqlal - Toulouse',
          education: 'Licence',
          bio: 'Étudiante en marketing, j\'aime les sorties en nature et les moments en famille...',
          traits: ['family-oriented', 'kind', 'creative'],
          goals: 'Cherche une relation authentique et sincère',
          similarMatches: [
            { id: 29, age: 25, mosque: 'Mosquée Al-Qasba - Nice', matchScore: 89 },
            { id: 30, age: 23, mosque: 'Mosquée Centrale - Paris', matchScore: 86 },
            { id: 31, age: 26, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 83 },
          ],
        },
        {
          id: 10,
          age: 33,
          gender: 'femme',
          mosque: 'Mosquée Al-Qasba - Nice',
          education: 'Master',
          bio: 'Entrepreneur indépendante, j\'aime l\'innovation et les défis. Cherche quelqu\'un de fort...',
          traits: ['ambitious', 'intellectual', 'family-oriented'],
          goals: 'Partenaire pour construire un projet de vie ensemble',
          similarMatches: [
            { id: 32, age: 34, mosque: 'Mosquée Centrale - Paris', matchScore: 91 },
            { id: 33, age: 32, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 88 },
            { id: 34, age: 35, mosque: 'Mosquée Assalam - Marseille', matchScore: 85 },
          ],
        },
        {
          id: 11,
          age: 28,
          gender: 'femme',
          mosque: 'Mosquée Centrale - Paris',
          education: 'Licence',
          bio: 'Infirmière dévouée, je suis empathique et j\'aime aider les autres...',
          traits: ['kind', 'family-oriented', 'spiritual'],
          goals: 'Cherche un compagnon pour une vie harmonieuse',
          similarMatches: [
            { id: 35, age: 29, mosque: 'Mosquée Al-Noor - Lyon', matchScore: 90 },
            { id: 36, age: 27, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 87 },
            { id: 37, age: 30, mosque: 'Mosquée Al-Qasba - Nice', matchScore: 84 },
          ],
        },
        {
          id: 12,
          age: 26,
          gender: 'femme',
          mosque: 'Mosquée Al-Noor - Lyon',
          education: 'Master',
          bio: 'Data scientist passionnée, j\'aime comprendre le monde à travers les données...',
          traits: ['intellectual', 'ambitious', 'kind'],
          goals: 'Cherche quelqu\'un avec qui partager mes passions',
          similarMatches: [
            { id: 38, age: 27, mosque: 'Mosquée Assalam - Marseille', matchScore: 89 },
            { id: 39, age: 25, mosque: 'Mosquée Centrale - Paris', matchScore: 86 },
            { id: 40, age: 28, mosque: 'Mosquée Istiqlal - Toulouse', matchScore: 83 },
          ],
        },
      ],
    };
  },

  computed: {
    filteredProfiles() {
      return this.allProfiles.filter((profile) => {
        // Gender filter
        if (this.filters.gender && profile.gender !== this.filters.gender) {
          return false;
        }

        // Age filter
        if (
          profile.age < this.filters.ageMin ||
          profile.age > this.filters.ageMax
        ) {
          return false;
        }

        // Mosque filter
        if (
          this.filters.mosque &&
          profile.mosque !== this.filters.mosque
        ) {
          return false;
        }

        // Education filter
        if (
          this.filters.education.length > 0 &&
          !this.filters.education.includes(profile.education.toLowerCase())
        ) {
          return false;
        }

        // Traits filter
        if (this.filters.traits.length > 0) {
          const hasTraits = this.filters.traits.some((trait) =>
            profile.traits.includes(trait)
          );
          if (!hasTraits) {
            return false;
          }
        }

        return true;
      });
    },

    paginatedProfiles() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredProfiles.slice(start, end);
    },

    totalCount() {
      return this.filteredProfiles.length;
    },

    displayedCount() {
      const end = Math.min(
        this.currentPage * this.itemsPerPage,
        this.filteredProfiles.length
      );
      return end;
    },

    totalPages() {
      return Math.ceil(this.filteredProfiles.length / this.itemsPerPage);
    },
  },

  methods: {
    applyFilters() {
      this.currentPage = 1;
      this.filtersActive = true;
      // In real app, would call API here
    },

    resetFilters() {
      this.filters = {
        gender: '',
        ageMin: 18,
        ageMax: 80,
        mosque: '',
        education: [],
        traits: [],
      };
      this.currentPage = 1;
      this.filtersActive = false;
    },

    truncateBio(bio) {
      return bio.length > 100 ? bio.substring(0, 100) + '...' : bio;
    },

    formatTrait(trait) {
      const traitMap = {
        kind: 'Bienveillant',
        ambitious: 'Ambitieux',
        'family-oriented': 'Familial',
        intellectual: 'Intellectuel',
        spiritual: 'Spirituel',
        creative: 'Créatif',
      };
      return traitMap[trait] || trait;
    },

    openProfileDetail(profile) {
      this.selectedProfile = profile;
      this.exchangeModalOpen = false;
    },

    closeModal() {
      this.selectedProfile = null;
    },

    openExchangeModal(profile) {
      this.selectedProfile = profile;
      this.exchangeModalOpen = true;
      this.exchangeMessage = '';
    },

    closeExchangeModal() {
      this.exchangeModalOpen = false;
      this.exchangeMessage = '';
    },

    async submitExchange() {
      this.isSubmitting = true;

      // Simulate API call
      setTimeout(() => {
        this.isSubmitting = false;
        this.exchangeModalOpen = false;
        this.showSuccess = true;

        setTimeout(() => {
          this.showSuccess = false;
        }, 3000);
      }, 1500);
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.scrollToTop();
      }
    },

    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.scrollToTop();
      }
    },

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
  },
};
</script>

<style scoped>
/* Color Variables */
:root {
  --sapphire-blue: #0f3a7d;
  --coral-pink: #ff6b6b;
  --teal: #17a2b8;
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.2);
}

/* Container */
.browse-profiles-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0f3a7d 0%, #1a5fa0 50%, #17a2b8 100%);
  overflow-x: hidden;
}

/* Hero Section */
.hero-section {
  position: relative;
  padding: 60px 20px;
  text-align: center;
  color: white;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.05);
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 10px;
  animation: fadeInDown 0.8s ease;
}

.hero-subtitle {
  font-size: 1.3rem;
  margin-bottom: 20px;
  opacity: 0.95;
  animation: fadeInUp 0.8s ease 0.2s both;
}

.hero-note {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: rgba(255, 255, 255, 0.15);
  padding: 12px 20px;
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  font-size: 0.95rem;
  animation: fadeInUp 0.8s ease 0.4s both;
}

.icon-info::before {
  content: 'ℹ';
  font-style: italic;
}

.islamic-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 400px;
  height: 300px;
  background-image: 
    repeating-linear-gradient(
      45deg,
      transparent,
      transparent 10px,
      rgba(255, 255, 255, 0.05) 10px,
      rgba(255, 255, 255, 0.05) 20px
    );
  opacity: 0.3;
  pointer-events: none;
}

/* Browse Wrapper */
.browse-wrapper {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 30px;
  padding: 40px 20px;
  max-width: 1600px;
  margin: 0 auto;
}

/* Filter Sidebar */
.filter-sidebar {
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: 20px;
  padding: 30px;
  height: fit-content;
  position: sticky;
  top: 20px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.filter-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 1px solid var(--glass-border);
}

.filter-header h2 {
  color: white;
  font-size: 1.3rem;
  margin: 0;
}

.reset-btn {
  background: transparent;
  color: var(--coral-pink);
  border: none;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 600;
}

.reset-btn:hover {
  opacity: 0.8;
  text-decoration: underline;
}

.filter-group {
  margin-bottom: 25px;
}

.filter-label {
  display: block;
  color: white;
  font-weight: 600;
  margin-bottom: 12px;
  font-size: 0.95rem;
}

.filter-options,
.filter-checkboxes {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.radio-option,
.checkbox-option {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  color: white;
  transition: all 0.3s ease;
}

.radio-option:hover,
.checkbox-option:hover {
  opacity: 0.8;
}

.radio-option input[type="radio"],
.checkbox-option input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: var(--coral-pink);
}

.filter-select {
  width: 100%;
  padding: 10px 12px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid var(--glass-border);
  border-radius: 8px;
  color: white;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.filter-select:hover {
  background: rgba(255, 255, 255, 0.15);
  border-color: var(--coral-pink);
}

.filter-select option {
  background: var(--sapphire-blue);
  color: white;
}

.age-inputs {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 10px;
  margin-bottom: 12px;
  align-items: center;
}

.age-input {
  padding: 8px 10px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid var(--glass-border);
  border-radius: 6px;
  color: white;
  font-size: 0.9rem;
}

.age-input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.age-dash {
  color: white;
  text-align: center;
}

.range-slider {
  width: 100%;
  margin-bottom: 8px;
  cursor: pointer;
  accent-color: var(--coral-pink);
}

.apply-filters-btn {
  width: 100%;
  padding: 12px 20px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 15px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.apply-filters-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

.apply-filters-btn:active {
  transform: translateY(0);
}

.icon-check::before {
  content: '✓';
}

/* Main Content */
.profiles-main {
  flex: 1;
}

.results-header {
  margin-bottom: 30px;
}

.results-title {
  color: white;
  font-size: 2rem;
  margin-bottom: 8px;
  font-weight: 700;
}

.results-count {
  color: rgba(255, 255, 255, 0.8);
  font-size: 1rem;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: white;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 20px;
  opacity: 0.6;
}

.icon-search::before {
  content: '🔍';
}

.empty-state h3 {
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.empty-state p {
  font-size: 1rem;
  opacity: 0.8;
  margin-bottom: 20px;
}

.empty-reset-btn {
  padding: 12px 30px;
  background: var(--coral-pink);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.empty-reset-btn:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

/* Profiles Grid */
.profiles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 25px;
  margin-bottom: 40px;
}

.profile-card {
  position: relative;
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: 20px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}

.profile-card:hover {
  transform: translateY(-8px);
  border-color: var(--coral-pink);
  box-shadow: 0 20px 40px rgba(255, 107, 107, 0.2);
}

.card-header {
  padding: 20px;
  background: linear-gradient(135deg, rgba(15, 58, 125, 0.4) 0%, rgba(23, 162, 184, 0.4) 100%);
  border-bottom: 1px solid var(--glass-border);
}

.avatar-container {
  position: relative;
  display: inline-block;
}

.anonymous-avatar {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  color: white;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.icon-mosque::before {
  content: '🕌';
}

.anonymous-badge {
  position: absolute;
  bottom: -5px;
  right: -5px;
  background: var(--teal);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.card-content {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.profile-info {
  margin-bottom: 15px;
}

.profile-age {
  color: white;
  font-size: 1.3rem;
  font-weight: 700;
  margin: 0 0 8px 0;
}

.profile-location,
.profile-education {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.95rem;
  margin: 6px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.icon-location::before,
.icon-graduation::before {
  font-size: 1.1rem;
}

.icon-location::before {
  content: '📍';
}

.icon-graduation::before {
  content: '🎓';
}

.profile-bio {
  color: rgba(255, 255, 255, 0.85);
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 15px;
  flex: 1;
}

.card-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.exchange-btn {
  padding: 12px 16px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
}

.exchange-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3);
}

.icon-heart::before {
  content: '❤️';
}

.more-btn {
  padding: 10px 16px;
  background: transparent;
  color: var(--teal);
  border: 1px solid var(--teal);
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.more-btn:hover {
  background: var(--teal);
  color: white;
  box-shadow: 0 4px 15px rgba(23, 162, 184, 0.2);
}

.icon-arrow::before {
  content: '→';
}

.card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: all 0.4s ease;
  pointer-events: none;
}

.profile-card:hover .card-overlay {
  opacity: 1;
}

.overlay-content {
  text-align: center;
}

.overlay-text {
  color: white;
  font-size: 1rem;
  font-weight: 600;
}

/* Pagination */
.pagination-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: 40px;
  padding: 30px 20px;
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: 20px;
}

.pagination-btn {
  padding: 10px 20px;
  background: transparent;
  color: white;
  border: 1px solid var(--glass-border);
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: var(--coral-pink);
  border-color: var(--coral-pink);
  transform: translateY(-2px);
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-info {
  color: white;
  font-weight: 600;
}

.icon-chevron-left::before,
.icon-chevron-right::before {
  content: '';
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: white;
  border-radius: 24px;
  max-width: 500px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  position: relative;
}

.modal-content.modal-exchange {
  max-width: 450px;
}

.modal-close {
  position: absolute;
  top: 20px;
  right: 20px;
  background: transparent;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: var(--sapphire-blue);
  transition: all 0.3s ease;
  z-index: 10;
}

.modal-close:hover {
  transform: rotate(90deg);
}

.icon-close::before {
  content: '✕';
}

.modal-header {
  padding: 40px 30px 30px;
  text-align: center;
  background: linear-gradient(135deg, rgba(15, 58, 125, 0.1) 0%, rgba(23, 162, 184, 0.1) 100%);
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.modal-avatar {
  width: 100px;
  height: 100px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  margin: 0 auto 15px;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.modal-badges {
  display: flex;
  justify-content: center;
  gap: 10px;
  flex-wrap: wrap;
}

.badge {
  background: var(--teal);
  color: white;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.modal-body {
  padding: 30px;
}

.modal-section {
  margin-bottom: 25px;
}

.modal-section:last-child {
  margin-bottom: 0;
}

.modal-section-title {
  color: var(--sapphire-blue);
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 15px;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.info-item {
  background: rgba(15, 58, 125, 0.05);
  padding: 12px 15px;
  border-radius: 10px;
  border-left: 3px solid var(--coral-pink);
}

.info-item label {
  display: block;
  color: var(--sapphire-blue);
  font-weight: 600;
  font-size: 0.85rem;
  margin-bottom: 5px;
}

.info-item p {
  color: var(--sapphire-blue);
  font-size: 0.95rem;
  margin: 0;
}

.modal-bio {
  color: var(--sapphire-blue);
  line-height: 1.6;
  font-size: 0.95rem;
}

.traits-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.trait-badge {
  background: linear-gradient(135deg, var(--teal) 0%, #1abc9c 100%);
  color: white;
  padding: 8px 14px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.similar-profiles {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.similar-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  background: rgba(15, 58, 125, 0.05);
  border-radius: 10px;
  border: 1px solid rgba(15, 58, 125, 0.1);
  transition: all 0.3s ease;
}

.similar-card:hover {
  background: rgba(15, 58, 125, 0.1);
  border-color: var(--coral-pink);
}

.similar-avatar {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.similar-info {
  flex: 1;
  min-width: 0;
}

.similar-age {
  color: var(--sapphire-blue);
  font-weight: 700;
  margin: 0;
  font-size: 0.95rem;
}

.similar-location {
  color: rgba(15, 58, 125, 0.7);
  font-size: 0.85rem;
  margin: 3px 0 0 0;
}

.similar-match-score {
  background: var(--coral-pink);
  color: white;
  padding: 6px 12px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.modal-footer {
  padding: 20px 30px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  background: rgba(15, 58, 125, 0.02);
}

.exchange-btn-large {
  width: 100%;
  padding: 14px 20px;
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.exchange-btn-large:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

/* Exchange Modal */
.exchange-header {
  padding: 40px 30px 20px;
  text-align: center;
  font-size: 2rem;
  margin-bottom: 10px;
}

.exchange-header h2 {
  color: var(--sapphire-blue);
  font-size: 1.4rem;
  margin: 15px 0 0 0;
  font-weight: 700;
}

.exchange-body {
  padding: 0 30px 30px;
}

.exchange-subtitle {
  color: rgba(15, 58, 125, 0.8);
  text-align: center;
  margin-bottom: 20px;
  font-size: 0.95rem;
}

.message-field {
  margin-bottom: 20px;
}

.message-field label {
  display: block;
  color: var(--sapphire-blue);
  font-weight: 600;
  margin-bottom: 10px;
  font-size: 0.95rem;
}

.message-field textarea {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid rgba(15, 58, 125, 0.2);
  border-radius: 10px;
  font-family: inherit;
  font-size: 0.95rem;
  color: var(--sapphire-blue);
  resize: vertical;
  transition: all 0.3s ease;
}

.message-field textarea:focus {
  outline: none;
  border-color: var(--coral-pink);
  box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
}

.message-hint {
  font-size: 0.8rem;
  color: rgba(15, 58, 125, 0.6);
  margin-top: 5px;
}

.exchange-footer {
  padding: 20px 30px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  display: flex;
  gap: 12px;
}

.btn-cancel,
.btn-confirm {
  flex: 1;
  padding: 12px 20px;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.btn-cancel {
  background: rgba(15, 58, 125, 0.1);
  color: var(--sapphire-blue);
  border: 1px solid rgba(15, 58, 125, 0.2);
}

.btn-cancel:hover {
  background: rgba(15, 58, 125, 0.15);
}

.btn-confirm {
  background: linear-gradient(135deg, var(--coral-pink) 0%, #ff8a7d 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.btn-confirm:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

.btn-confirm:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Success Toast */
.success-toast {
  position: fixed;
  bottom: 30px;
  right: 30px;
  background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
  color: white;
  padding: 16px 24px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  font-weight: 600;
  z-index: 2000;
}

.icon-check-circle::before {
  content: '✓';
  font-size: 1.3rem;
}

/* Animations */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-content,
.modal-leave-to .modal-content {
  transform: scale(0.95);
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

/* Mobile Responsive */
@media (max-width: 1024px) {
  .browse-wrapper {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .filter-sidebar {
    position: static;
    grid-column: 1 / -1;
  }

  .profiles-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2rem;
  }

  .hero-subtitle {
    font-size: 1.1rem;
  }

  .browse-wrapper {
    padding: 20px 15px;
  }

  .profiles-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .profile-card {
    border-radius: 16px;
  }

  .modal-content {
    max-width: 95vw;
    border-radius: 20px;
  }

  .success-toast {
    bottom: 20px;
    right: 20px;
    left: 20px;
  }

  .results-title {
    font-size: 1.5rem;
  }

  .pagination-container {
    flex-wrap: wrap;
    gap: 15px;
    padding: 20px;
  }
}

@media (max-width: 480px) {
  .hero-section {
    padding: 40px 15px;
  }

  .hero-title {
    font-size: 1.5rem;
  }

  .hero-subtitle {
    font-size: 1rem;
  }

  .filter-sidebar {
    padding: 20px;
  }

  .profiles-grid {
    gap: 15px;
  }

  .profile-card {
    border-radius: 14px;
  }

  .card-header {
    padding: 15px;
  }

  .card-content {
    padding: 15px;
  }

  .card-actions {
    gap: 8px;
  }

  .exchange-btn,
  .more-btn {
    padding: 10px 12px;
    font-size: 0.85rem;
  }

  .modal-content {
    border-radius: 16px;
    max-height: 95vh;
  }

  .modal-header {
    padding: 30px 20px 20px;
  }

  .modal-body {
    padding: 20px;
  }

  .modal-footer {
    padding: 15px 20px;
  }

  .modal-close {
    top: 15px;
    right: 15px;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .pagination-btn {
    padding: 8px 15px;
    font-size: 0.85rem;
  }
}
</style>
