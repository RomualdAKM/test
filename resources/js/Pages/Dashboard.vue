<template>
    <!-- Head Component pour définir le titre de la page -->
    <Head title="Dashboard" />

    <!-- Layout pour les utilisateurs authentifiés -->
    <AuthenticatedLayout>
        <!-- Header de la page -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        
        <!-- Modale de création de marque -->
        <div v-if="showCreateModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Créer une nouvelle marque</h2>
                <!-- Formulaire de création de marque -->
                <form @submit.prevent="createMark">
                    <input type="text" v-model="newMark.name" placeholder="Nom de la marque" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">
                    <select multiple v-model="selectedProducts" required class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <!-- Option de sélection des produits -->
                        <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                    </select>
                    <!-- Boutons de soumission et d'annulation -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Créer</button>
                        <button @click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 ml-2 rounded">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modale de modification de marque -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Modifier la marque</h2>
                <!-- Formulaire de modification de marque -->
                <form @submit.prevent="updateMark">
                    <input type="text" v-model="editedMark.name" placeholder="Nom de la marque" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">
                    <select multiple v-model="editedProducts" required class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <!-- Options pré-sélectionnées des produits à modifier -->
                        <option v-for="product in products" :key="product.id" :value="product.id" :selected="editedMark.products.some(p => p.id === product.id)">{{ product.name }}</option>
                    </select>
                    <!-- Boutons de soumission et d'annulation -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
                        <button @click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 ml-2 rounded">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Section de liste des marques -->
                <div class="flex justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Listes des marques</div>
                    <!-- Bouton d'ajout d'une nouvelle marque -->
                    <button @click="showCreateModal = true" type="button" class="mt-4 p-4 text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-25 py-1.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Ajouter une marque</button>
                </div>
                <!-- Tableau des marques -->
                <div class="mt-10 overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                    Nom de la marque
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Produit(s)
                                </th>
                                <th scope="col"  class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Affichage des marques -->
                            <tr v-for="(mark, index) in marks" :key="index" class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    {{ mark.name }}
                                </td>
                                <td class="px-6 py-4">
                                    <!-- Affichage des produits associés à chaque marque -->
                                    <span v-for="(product, pIndex) in mark.products" :key="pIndex">{{ product.name }}</span>
                                </td>
                                <!-- Boutons d'édition et de suppression de marque -->
                                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                                    <button @click="editMark(mark)" class="mt-4 p-4 text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-25 py-1.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Modifier</button>
                                    <button @click="deleteMark(mark.id)" class="mt-4 p-4 text-red-900 hover:text-white border border-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-25 py-1.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
// Importation des composants et des utilitaires nécessaires
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia';

// Définition des propriétés requises
defineProps({
    marks: {
        type: Array,
        default: () => [],
    },
    products: {
        type: Array,
        default: () => [],
    },
});

// Définition des variables réactives
const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedProducts = ref([]);
const newMark = ref({ name: '', products: [] });
const editedMark = ref(null);
const editedProducts = ref([]);

// Fonction pour fermer les modales
const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
};

// Fonction pour créer une nouvelle marque
const createMark = () => {
    Inertia.post(route('marks.store'), {
        name: newMark.value.name,
        products: selectedProducts.value,
    });
    closeModal();
};

// Fonction pour mettre à jour une marque existante
const updateMark = () => {
    Inertia.put(route('marks.update', { mark: editedMark.value.id }), {
        name: editedMark.value.name,
        products: editedProducts.value,
    });
    closeModal();
};

// Fonction pour éditer une marque
const editMark = (mark) => {
    editedMark.value = mark;
    editedProducts.value = mark.products.map(product => product.id);
    showEditModal.value = true;
};

// Fonction pour supprimer une marque
const deleteMark = (markId) => {
    if (confirm('Voulez-vous vraiment supprimer cette marque ?')) {
        Inertia.delete(route('marks.destroy', { mark: markId }));
    }
};

// Réinitialisation des valeurs lors du montage du composant
onMounted(() => {
    selectedProducts.value = [];
    editedMark.value = null;
    editedProducts.value = [];
});

</script>
