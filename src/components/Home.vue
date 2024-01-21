<template>
  <main class="home-page">
    <div>
      <div class="titre">
        <img src="../../favicon.ico" alt="Logo" class="logo"/>
        <h1>BiblioTech : l'outil de gestion de la médiathèque de la ville de Saint-Barthélemy-d'Anjou</h1>
      </div>

      <h2>Liste des documents</h2>
      
      <!-- Tri par titre ou par côte -->
      <div class="toggle-button-container">
        <label :class="{ active: selectedSortingCriteria === 'titre' }" @click="sortByTitre">
          <input type="radio" v-model="selectedSortingCriteria" value="titre">
          Titre
        </label>

        <label :class="{ active: selectedSortingCriteria === 'cote' }" @click="sortByCote2">
          <input type="radio" v-model="selectedSortingCriteria" value="cote">
          Côte
        </label>
      </div>

      <!-- Cases à cocher pour le tri par type de documents -->
      <div class="checkbox-container">
        <label>
          <input type="checkbox" v-model="selectedDocumentTypes" value="livre">
          Livres
        </label>
        <label>
          <input type="checkbox" v-model="selectedDocumentTypes" value="journal">
          Journaux
        </label>
        <label>
          <input type="checkbox" v-model="selectedDocumentTypes" value="cdrom">
          CD-ROM
        </label>
        <label>
          <input type="checkbox" v-model="selectedDocumentTypes" value="microfilm">
          Microfilms
        </label>
      </div>
      
      <!-- Barre de recherche -->
      <div class="search-bar">
        <input type="text" v-model="searchQuery" placeholder="Rechercher par titre..." />
      </div>

      <!-- Section pour afficher la liste des documents -->
      <div class="doc-container">
        <div v-for="doc in filteredResults" :key="doc.id" class="doc-frame">
          <p>Type du document : {{ getDocumentType(doc.type) }}</p>
          <!-- Utilisation d'une expression pour construire le chemin complet -->
          <img :src="doc.chemin_image" alt="Image du document" class="doc-image" />
          <div class="doc-details">
            <p class="doc-title">
              <strong>{{ doc.titre }}</strong>
            </p>
            <p v-if="doc.auteur" class="doc-author">
              <strong>Auteur(s) : </strong> {{ doc.auteur }}
            </p>
            <p v-if="doc.date" class="doc-date">
              <strong>Date : </strong> {{ doc.date }}
            </p>
            <p class="doc-id">
              <strong>Côte : </strong> {{ doc.cote }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const allDocuments = ref([]);
const selectedSortingCriteria = ref('titre');
const selectedDocumentTypes = ref(['livre', 'journal', 'cdrom', 'microfilm']);
const searchQuery = ref('');

const sortByTitle = (a, b) => {
  const titleA = a.titre.toUpperCase();
  const titleB = b.titre.toUpperCase();

  return titleA.localeCompare(titleB);
};

const sortByCote = (a, b) => {
  const coteA = parseInt(a.cote, 10) || 0;
  const coteB = parseInt(b.cote, 10) || 0;

  return coteA - coteB;
};

const sortByTitre = () => {
  selectedSortingCriteria.value = 'titre';
  allDocuments.value.sort(sortByTitle);
};

const sortByCote2 = () => {
  selectedSortingCriteria.value = 'cote';
  allDocuments.value.sort(sortByCote);
};

const fetchAllDocuments = async () => {
  try {
    const responseBooks = await axios.get('http://localhost/api_BiblioTech.php?table=livres');
    const responseJournals = await axios.get('http://localhost/api_BiblioTech.php?table=journaux');
    const responseCDs = await axios.get('http://localhost/api_BiblioTech.php?table=cdrom');
    const responseMicrofilms = await axios.get('http://localhost/api_BiblioTech.php?table=microfilms');

    // Fusion des listes des différents types de documents
    allDocuments.value = [
      ...responseBooks.data.map(book => ({ ...book, type: 'livre' })),
      ...responseJournals.data.map(journal => ({ ...journal, type: 'journal' })),
      ...responseCDs.data.map(cd => ({ ...cd, type: 'cdrom' })),
      ...responseMicrofilms.data.map(microfilm => ({ ...microfilm, type: 'microfilm' })),
    ];

    // Tri initial
    sortByTitre();
  } catch (error) {
    console.log("Erreur lors de la récupération des données de tous les documents", error);
  }
};

onMounted(fetchAllDocuments);

const getDocumentType = (type) => {
  switch (type) {
    case 'livre':
      return 'Livre';
    case 'journal':
      return 'Journal';
    case 'cdrom':
      return 'CD-ROM';
    case 'microfilm':
      return 'Microfilm';
    default:
      return 'Inconnu';
  }
};

const filteredResults = computed(() => {
  const searchLowerCase = searchQuery.value.trim().toLowerCase();

  if (searchQuery.value.trim().length === 0) {
    return allDocuments.value.filter(doc => selectedDocumentTypes.value.includes(doc.type));
  }

  return allDocuments.value.filter(doc => {
    const titleLowerCase = doc.titre.toLowerCase();
    const matchesSearch = titleLowerCase.startsWith(searchLowerCase);
    const matchesType = selectedDocumentTypes.value.includes(doc.type);

    return matchesSearch && matchesType;
  });
});


</script>

<style lang="scss">
.titre{
  display: flex;
  align-items: center;
  text-align: center;
  margin-bottom: 5%;

  .logo{
    margin-right: 5%;
  }
}

h2{
  text-align: center;
  margin-bottom: 3%;
}

.toggle-button-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 200px; 
  margin: 10px auto;
}

.search-bar {
  margin-bottom: 20px;
}

.search-bar input {
  width: 25%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

label {
  flex: 1;
  text-align: center;
  padding: 10px;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

label:hover {
  background-color: #3498db;
}

label.active {
  background-color: #2980b9;
  color: white;
}

.toggle-button-slider {
  width: 50px; 
  height: 30px; 
  background-color: #ecf0f1;
  border-radius: 15px;
  position: relative;
  transition: transform 0.3s ease;
}

input[type="radio"] {
  display: none;
}

input[type="radio"]:checked + .toggle-button-slider {
  transform: translateX(100%);
}

.doc-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}

.doc-frame {
  border: 2px solid #ccc;
  border-radius: 8px;
  margin: 10px;
  padding: 10px;
  text-align: center;
  width: calc(33.33% - 20px); /* 3 encadrés par ligne */
  box-sizing: border-box;
}

.doc-frame:hover {
  transition: 0.5s;
  border: 2px solid #000000;
  border-radius: 8px;
}

.doc-image {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
  max-height: 350px; 
}

.doc-title {
  font-size: 150%;
  font-weight: bold;
  margin-bottom: 5px;
}

.doc-author {
  margin: 0;
}

/* Styles pour les écrans plus petits */
@media (max-width: 768px) {
  .book-frame {
    width: calc(50% - 20px); /* 2 encadrés par ligne pour les écrans plus petits */
  }
}

@media (max-width: 480px) {
  .book-frame {
    width: 100%; /* 1 encadré par ligne pour les écrans encore plus petits */
  }
}
</style>
