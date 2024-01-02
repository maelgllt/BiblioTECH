<template>
  <main class="about-page">
    <h1>Compte</h1>

    <div class="forms">
      <!-- Formulaire de Connexion -->
      <div class="login-form">
        <h2>Connexion</h2>
        <form @submit.prevent="login">
          <input type="text" id="loginUsername" v-model="loginUsername" placeholder="Nom d'utilisateur" required />

          <input type="password" id="loginPassword" v-model="loginPassword" placeholder="Mot de passe" required />

          <button type="submit">Se connecter</button>
        </form>
      </div>

      <!--On considère que l'inscription concerne uniquement les utilisateurs et non les employés + les bénévoles. 
        Ces derniers sont ajoutés directement en bdd puisque on considère qu'ils y ont accès-->

      <!-- Formulaire d'Inscription -->
      <div class="signup-form">
        <h2>Inscription</h2>
        <form @submit.prevent="signup">
          <input type="text" id="signupNom" v-model="signupNom" placeholder="Nom" required />

          <input type="text" id="signupPrenom" v-model="signupPrenom" placeholder="Prénom" required />

          <input type="text" id="signupUsername" v-model="signupUsername" placeholder="Nom d'utilisateur" required />

          <input type="email" id="signupEmail" v-model="signupEmail" placeholder="Email" required />

          <input type="tel" id="signupTelephone" v-model="signupTelephone" placeholder="Téléphone" required />

          <input type="text" id="signupAdresse" v-model="signupAdresse" placeholder="Adresse" required />

          <input type="password" id="signupPassword" v-model="signupPassword" placeholder="Mot de passe" required />

          <input type="password" id="signupConfirmPassword" v-model="signupConfirmPassword" placeholder="Confirmer mot de passe" required />

          <button type="submit">S'inscrire</button>

          <span class="error" v-if="signupClicked && signupEmail !== '' && !isValidEmail">Adresse e-mail invalide</span>
          <span class="error" v-if="signupClicked && signupPassword !== '' && !isStrongPassword">Le mot de passe doit contenir au moins 8 caractères avec une majuscule, une minuscule, un chiffre et un caractère spécial !</span>
          <span class="error" v-if="signupClicked && !isPasswordConfirmed">Les mots de passe ne correspondent pas.</span>
        </form>
      </div>
    </div>

    <p class="success" v-if="ValidRegister">Inscription réussie !</p>

    <p class="success" v-if="ValidLogin == 1">Connexion réussie !</p>
    <p class="success" v-else-if="ValidLogin == 2">Nom d'utilisateur ou mot de passe incorrect</p>
    <p class="success" v-else></p>
  </main>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';

const loginUsername = ref('');
const loginPassword = ref('');

const signupNom = ref('');
const signupPrenom = ref('');
const signupUsername = ref('');
const signupEmail = ref('');
const signupTelephone = ref('');
const signupAdresse = ref('');
const signupPassword = ref('');
const signupConfirmPassword = ref('');

// Variable pour indiquer si le bouton "S'inscrire" a été cliqué
let signupClicked = ref(false);

let ValidRegister = ref(false);
let ValidLogin = ref(0);

const login = async () => {
  try {
    const response = await axios.post('http://localhost/api_BiblioTech.php?action=login', {
      action: 'login',
      username: loginUsername.value,
      password: loginPassword.value,
    });

    //console.log(response.data);
    
    if (response.data == 1){ //on vérifie si la réponse est 1 car si l'utilisateur entre les bons identifiants, le serveur renvoie true qui est égal à 1
      console.log("Connexion réussie !");
      ValidLogin.value = 1;
    }
    else{
      console.log("Nom d\'utilisateur ou mot de passe incorrect");
      ValidLogin.value = 2;
    }
  } catch (error) {
    console.log('Erreur lors de la connexion', error);
    // Ajoutez ici la logique pour gérer l'erreur après une tentative de connexion infructueuse
  }
};

const signup = async () => {

  signupClicked.value = true;  

  // Valider le mot de passe seulement si la propriété calculée isPasswordValid est vraie
  if (!isPasswordValid.value) {
    return; // Arrêtez l'exécution si la validation échoue
  }

  const userData = {
    action: 'signup',
    username: signupUsername.value,
    password: signupPassword.value,
    nom: signupNom.value,
    prenom: signupPrenom.value,
    email: signupEmail.value,
    telephone: signupTelephone.value,
    adresse: signupAdresse.value,
  };

  try {
    const response = await axios.post('http://localhost/api_BiblioTech.php', userData);
    console.log(response.data);
    ValidRegister.value = true;
  } catch (error) {
    console.log('Erreur lors de l\'inscription', error);
  }
};


const isValidEmail = computed(() => {
  return signupClicked ? /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(signupEmail.value) : null;
});

const isStrongPassword = computed(() => {
  return signupClicked ? /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(signupPassword.value) : null;
});

const isPasswordConfirmed = computed(() => {
  return signupClicked ? signupPassword.value === signupConfirmPassword.value : null;
});

const isPasswordValid = computed(() => {
  return isValidEmail.value === true && isStrongPassword.value === true && isPasswordConfirmed.value === true;
});

</script>


<style lang="scss">
.about-page {
  h1 {
    text-align: center;
    margin-bottom: 20px;
  }

  h2{
    text-align: center;
  }

  .forms {
    display: flex;
    justify-content: space-evenly;
    align-items: center;

    .login-form,
    .signup-form {
      padding: 30px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: min-content;

      form {
        display: flex;
        flex-direction: column;
        margin-top: 15%;

        input {
          margin-bottom: 16px;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
          font-size: 16px;

          &:focus {
            border-color: #3498db;
          }
        }

        button {
          margin-top: 16px;
          padding: 10px;
          background: var(--primary);
          color: #fff;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          font-size: 16px;

          &:hover {
            background: var(--primary-alt);
          }
        }

        .error {
          color: red;
          margin-top: 8px;
          font-size: small;
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
