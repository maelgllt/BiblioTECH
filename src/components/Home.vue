<template>
  <main class="home-page">
    <div>
      <h1>Bienvenue sur BiblioTech, l'application de gestion de la médiathèque de la ville de Saint-Barthélemy-d'Anjou</h1>
      <div class="book-container">
        <div v-for="book in books" :key="book.id" class="book-frame">
          <!-- Utilisation d'une expression pour construire le chemin complet -->
          <img :src="book.chemin_image" alt="Image du livre" class="book-image" />
          <div class="book-details">
            <p class="book-title">
              <strong>{{ book.titre }}</strong>
            </p>
            <p class="book-author">
              <strong>Auteur(s) : </strong> {{ book.auteur }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const books = ref([]);

const fetchBooks = async () => {
  try {
    const response = await axios.get('http://localhost/api_BiblioTech.php?table=livres');
    books.value = response.data;
  } catch (error) {
    console.log("Erreur lors de la récupération des données de l'API", error);
  }
};

onMounted(fetchBooks);
</script>

<style lang="scss">
h1 {
  text-align: center;
}

.book-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}

.book-frame {
  border: 2px solid #ccc;
  border-radius: 8px;
  margin: 10px;
  padding: 10px;
  text-align: center;
  width: calc(33.33% - 20px); /* 3 encadrés par ligne */
  box-sizing: border-box;
}

.book-frame:hover {
  transition: 0.5s;
  border: 2px solid #000000;
  border-radius: 8px;
}

.book-image {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
  max-height: 350px; /* Taille maximale de l'image en hauteur */
}

.book-title {
  font-size: 150%;
  font-weight: bold;
  margin-bottom: 5px;
}

.book-author {
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
