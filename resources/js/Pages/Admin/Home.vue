<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import Biodata from '../../Components/Admin/Biodatas/Biodata.vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Header from '../../Components/Admin/Header.vue';
import Content from '../../Components/Admin/Contents/Content.vue';
import Proposals from '../../Components/Admin/Proposals/Proposal.vue';
import Others from '../../Components/Admin/Others/Others.vue';
import Approved from '../../Components/Admin/Approved/Approved.vue';
import PhotoApproval from '../../Components/Admin/Photos/PhotoApproval.vue';


defineProps({
    translations: {
        type: Object,
    },
    front_end_translations: {
        type: Object,
    },
    districts: {
        type: Object,
    },
    locale: {
        type: String,
    },
    locales: {
        type: Array,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    all_biodatas: {
        type: Object,
    },
    biodata_updates: {
        type: Object,
    },
    all_proposals: {
        type: Object,
    },
    all_terms: {
        type: Object,
    },
});


// initializing
const page = usePage();
// const csrf_token = page.props.csrf_token;
// const user_id = page.props.auth.user.id;
const selectedTab = ref(0);
const allBiodatas = ref({});
const allProposals = ref({});
const allTerms = ref({});


function changeTab(index) {
    selectedTab.value = index;
}


const onUpdateAllBiodatas = (biodatas) => {
    allBiodatas.value = biodatas;
}

const onUpdateAllProposals = (proposals) => {
    allProposals.value = proposals;
}

const onUpdateAllTerms = (terms) => {
    allTerms.value = terms;
}


onMounted(() => {

    setTimeout(() => {
        allBiodatas.value = page.props.all_biodatas;
        allProposals.value = page.props.all_proposals;
        allTerms.value = page.props.all_terms;
    }, 500);

});


</script>


<template>


    <Head title="Dashboard" />

    <AdminLayout :translations :locale :locales>

        <div class="shadibari-container-main min-h-screen bg-gradient-to-br from-[#0D2218] via-[#1a3a28] to-[#0D2218]">

            <Header :translations :locale :locales :canLogin :canRegister />

            <div class="container max-w-screen-xl mx-auto">
                <div class="biodata_main w-full min-h-screen">
                    <TabGroup :selectedIndex="selectedTab" @change="changeTab">

                        <TabList class="flex gap-3 border-b border-[#ff6b6b]/20 pb-4 overflow-x-auto scroll-smooth">
                            <Tab v-slot="{ selected }" as="template">
                                <button
                                    :class="['px-4 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all focus:outline-none focus:ring-2 focus:ring-[#ff6b6b]/40',
                                    selected 
                                        ? 'bg-[#ff6b6b]/20 text-[#E4B84A] border border-[#ff6b6b]/40 shadow-lg shadow-[#ff6b6b]/10' 
                                        : 'text-[#ff6b6b]/60 hover:text-[#ff6b6b] hover:bg-white/5 border border-transparent']">
                                    👥 Profils
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button
                                    :class="['px-4 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all focus:outline-none focus:ring-2 focus:ring-[#ff6b6b]/40',
                                    selected 
                                        ? 'bg-[#ff6b6b]/20 text-[#E4B84A] border border-[#ff6b6b]/40 shadow-lg shadow-[#ff6b6b]/10' 
                                        : 'text-[#ff6b6b]/60 hover:text-[#ff6b6b] hover:bg-white/5 border border-transparent']">
                                    💍 Propositions
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button
                                    :class="['px-4 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all focus:outline-none focus:ring-2 focus:ring-[#ff6b6b]/40',
                                    selected 
                                        ? 'bg-[#ff6b6b]/20 text-[#E4B84A] border border-[#ff6b6b]/40 shadow-lg shadow-[#ff6b6b]/10' 
                                        : 'text-[#ff6b6b]/60 hover:text-[#ff6b6b] hover:bg-white/5 border border-transparent']">
                                    ✅ Approuvés
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button
                                    :class="['px-4 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all focus:outline-none focus:ring-2 focus:ring-[#ff6b6b]/40',
                                    selected 
                                        ? 'bg-[#ff6b6b]/20 text-[#E4B84A] border border-[#ff6b6b]/40 shadow-lg shadow-[#ff6b6b]/10' 
                                        : 'text-[#ff6b6b]/60 hover:text-[#ff6b6b] hover:bg-white/5 border border-transparent']">
                                    ⚙️ Autres
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <button
                                    :class="['px-4 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all focus:outline-none focus:ring-2 focus:ring-[#ff6b6b]/40',
                                    selected 
                                        ? 'bg-[#ff6b6b]/20 text-[#E4B84A] border border-[#ff6b6b]/40 shadow-lg shadow-[#ff6b6b]/10' 
                                        : 'text-[#ff6b6b]/60 hover:text-[#ff6b6b] hover:bg-white/5 border border-transparent']">
                                    📝 Contenus
                                </button>
                            </Tab>

                        </TabList>


                        <TabPanels class="mt-6">

                            <TabPanel :class="['rounded-2xl bg-white/5 backdrop-blur-md p-6 border border-white/10 focus:outline-none']">

                                <Biodata :translations :locale :locales :front_end_translations :all_biodatas="allBiodatas" :districts @onUpdateAllBiodatas="onUpdateAllBiodatas" />

                            </TabPanel>

                            <TabPanel :class="['rounded-2xl bg-white p-3 shadow-sm ring-1 ring-[#0f3a7d]/[.06] focus:outline-none',
                            ]">

                                <Proposals :translations :locale :locales :front_end_translations :all_biodatas="allBiodatas" :all_proposals="allProposals" :districts @onUpdateAllProposals="onUpdateAllProposals" />

                            </TabPanel>

                            <TabPanel :class="['rounded-2xl bg-white p-3 shadow-sm ring-1 ring-[#0f3a7d]/[.06] focus:outline-none',
                            ]">

                                <Approved :translations :locale :locales :front_end_translations :all_biodatas="allBiodatas" :biodata_updates :all_proposals="allProposals" :districts  @onUpdateAllBiodatas="onUpdateAllBiodatas" />

                            </TabPanel>

                            <TabPanel :class="['rounded-2xl bg-white p-3 shadow-sm ring-1 ring-[#0f3a7d]/[.06] focus:outline-none',
                            ]">

                                <Others :translations :locale :locales :front_end_translations :all_biodatas="allBiodatas" :districts :all_terms="allTerms" @onUpdateAllTerms="onUpdateAllTerms" />

                            </TabPanel>

                            <TabPanel :class="['rounded-2xl bg-white/5 backdrop-blur-md p-6 border border-white/10 focus:outline-none']">

                                <PhotoApproval />

                            </TabPanel>

                            <TabPanel :class="['rounded-2xl bg-white p-3 shadow-sm ring-1 ring-[#0f3a7d]/[.06] focus:outline-none',
                            ]">

                                <Content :translations />

                            </TabPanel>

                        </TabPanels>

                    </TabGroup>

                </div>
            </div>

        </div>

    </AdminLayout>

</template>

<style>
.logout_image{
    background: #fef1f6;
    border-radius: 4px;
    width: 25px;
    height: 25px;
    filter: grayscale(100%);
    transition: filter .29s ease 0s;
    padding: 5px;
    border-radius: 4px;
    object-fit: contain;
    object-position: center;
}
.od-localization-container {
    padding-left: 0 !important;
}
</style>
