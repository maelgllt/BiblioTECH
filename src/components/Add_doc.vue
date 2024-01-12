<template>
    <main class="add-doc-page">
      <div class="titre">
        <img src="../../favicon.ico" alt="Logo" class="logo"/>
        <h1>BiblioTech : l'outil de gestion de la médiathèque de la ville de Saint-Barthélemy-d'Anjou</h1>
      </div>
      <h2>Ajouter un document</h2>
  
      <div class="add-doc-form">
        <form @submit.prevent="addDocument">
          <select id="docType" v-model="docType" @change="handleDocTypeChange" required>
            <option value="Livre">Livre</option>
            <option value="Journal">Journal</option>
            <option value="CD-ROM">CD-ROM</option>
            <option value="Microfilms">Microfilms</option>
          </select>
  
          <input type="text" id="docTitle" v-model="docTitle" placeholder="Titre du document" required />

          <div v-if="showAuthorField">
            <input type="text" id="docAuthor" v-model="docAuthor" placeholder="Auteur" required />
          </div>

          <div v-if="showDateField">
            <input type="date" id="docDate" v-model="docDate" required />
          </div>
  
          <button type="submit">Ajouter</button>
        </form>
      </div>
      <p class="success" v-if="apiResponse">{{ apiResponse }}</p>
    </main>
  </template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const docType = ref("Livre");
const docTitle = ref("");
const docAuthor = ref("");
const docDate = ref("");
const showAuthorField = ref(true);
const showDateField = ref(false);
const apiResponse = ref("");

const handleDocTypeChange = () => {
  // Afficher ou masquer les champs en fonction du type de document sélectionné
  showAuthorField.value = docType.value === "Livre" || docType.value === "CD-ROM";
  showDateField.value = docType.value === "Journal" || docType.value === "Microfilms";
};

const addDocument = async () => {
  try {
    const response = await axios.post('http://localhost/api_BiblioTech.php', {
      action: 'add_doc',
      docType: docType.value,
      docTitle: docTitle.value,
      docAuthor: docAuthor.value,
      docDate: docDate.value
    });

    apiResponse.value = "Document ajouté avec succès";
  } catch (error) {
    console.error('Erreur lors de l\'ajout du document', error);
    apiResponse.value = "Erreur lors de l'ajout du document";
  }
};

</script>

<style lang="scss">
.add-doc-page {
  .titre {
    display: flex;
    align-items: center;
    text-align: center;
    margin-bottom: 5%;

    .logo {
      margin-right: 5%;
    }
  }

  h2{
    text-align: center;
    margin-bottom: 5%;
  }

  .add-doc-form {
    width: 400px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;

    form {
      display: flex;
      flex-direction: column;

      select,
      input,
      button {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        width: 100%;
      }

      button {
        background: var(--primary);
        color: #fff;
        cursor: pointer;

        &:hover {
            background: var(--primary-alt);
        }
      }
    }
  }
  .success{
    text-align: center;
    margin-top: 3%;
    font-size: x-large;
  }
}
</style>
