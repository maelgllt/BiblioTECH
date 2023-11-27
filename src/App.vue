<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const books = ref([]);
const fetchBooks = async () => {
  try {
    const response = await axios.get('http://localhost/api_BiblioTech.php');
    books.value = response.data;
  } catch (error){
    console.log('Erreur lors de la récupération des données de l\'API', error);
  }
};

onMounted(fetchBooks);

</script>

<template>
  <div>
    <h1>Bienvenue sur BiblioTech, l'application de gestion de la médiathèque de la ville de Saint-Barthélemy-d'Anjou</h1>
    <ul v-for="book in books" :key="book.id">
      <p id="p1">
        <strong>Livre</strong> {{ book.cote_livres }}
      </p>
      <li>
        <strong>Titre : </strong> {{ book.titre }}
      </li>
      <li>
        <strong>Auteur(s) : </strong> {{ book.auteur }}
      </li>
    </ul>
  </div>
  
</template>

<style>
#p1{
  text-decoration: underline;
}
</style>