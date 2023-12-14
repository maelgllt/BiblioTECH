<template>
    <main class="about-page">
      <h1>Compte</h1>
  
      <!-- Formulaire de Connexion -->
      <div class="login-form">
        <h2>Connexion</h2>
        <form @submit.prevent="login">
          <label for="loginUsername">Nom d'utilisateur:</label>
          <input type="text" id="loginUsername" v-model="loginUsername" required />
  
          <label for="loginPassword">Mot de passe:</label>
          <input type="password" id="loginPassword" v-model="loginPassword" required />
  
          <button type="submit">Se connecter</button>
        </form>
      </div>
  
      <!-- Formulaire d'Inscription -->
      <div class="signup-form">
        <h2>Inscription</h2>
        <form @submit.prevent="signup">
          <label for="signupNom">Nom :</label>
          <input type="text" id="signupNom" v-model="signupNom" required />
  
          <label for="signupPrenom">Prénom :</label>
          <input type="text" id="signupPrenom" v-model="signupPrenom" required />
  
          <label for="signupEmail">Email :</label>
          <input type="email" id="signupEmail" v-model="signupEmail" required />
  
          <label for="signupTelephone">Téléphone :</label>
          <input type="tel" id="signupTelephone" v-model="signupTelephone" required />
  
          <label for="signupAdresse">Adresse :</label>
          <input type="text" id="signupAdresse" v-model="signupAdresse" required />
  
          <label for="signupPassword">Mot de passe:</label>
          <input type="password" id="signupPassword" v-model="signupPassword" required />
  
          <button type="submit">S'inscrire</button>
        </form>
      </div>
    </main>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import bcrypt from 'bcryptjs';
  
  // Données pour le formulaire de connexion
  const loginUsername = ref('');
  const loginPassword = ref('');
  
  // Données pour le formulaire d'inscription
  const signupNom = ref('');
  const signupPrenom = ref('');
  const signupEmail = ref('');
  const signupTelephone = ref('');
  const signupAdresse = ref('');
  const signupPassword = ref('');
  
  const login = async () => {
    try {
      const response = await axios.post('http://localhost/api_BiblioTech.php?action=login', {
        username: loginUsername.value,
        password: loginPassword.value,
      });
  
      console.log(response.data);
  
      // Ajoutez ici la logique pour gérer la réponse du serveur après la connexion réussie
      // Par exemple, vous pourriez rediriger l'utilisateur vers une autre page.
    } catch (error) {
      console.log('Erreur lors de la connexion', error);
      // Ajoutez ici la logique pour gérer l'erreur après une tentative de connexion infructueuse
    }
  };
  
  const signup = async () => {
    const hashedPassword = await hashPassword(signupPassword.value);
  
    const userData = {
      username: signupNom.value, // Nom d'utilisateur est défini ici comme le nom
      password: hashedPassword,
      nom: signupNom.value,
      prenom: signupPrenom.value,
      email: signupEmail.value,
      telephone: signupTelephone.value,
      adresse: signupAdresse.value,
    };
  
    try {
      const response = await axios.post('http://localhost/api_BiblioTech.php?action=signup', userData);
      console.log(response.data);
    } catch (error) {
      console.log('Erreur lors de l\'inscription', error);
    }
  };
  
  const hashPassword = async (password) => {
    const saltRounds = 10;
    const hashedPassword = await bcrypt.hash(password, saltRounds);
    return hashedPassword;
  };
  
  </script>
  
  <style lang="sass">
    /* Ajoutez vos styles ici */
  </style>
  