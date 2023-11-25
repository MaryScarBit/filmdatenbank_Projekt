<template>
  <div>
    <h2>Login</h2>
    <form>
      <div class="form-group">
        <label for="email">E-Mail:</label>
        <input v-model="email" type="text" id="email" required v-on:keyup.enter="onEnter"/>
      </div>

      <div class="form-group">
        <label for="password">Passwort:</label>
        <input v-model="password" type="password" id="password" required v-on:keyup.enter="onEnter"/>
      </div>

      <div class="form-group">
        <button type="button" @click="login">Einloggen</button>
        <button type="button" @click="register">Registrieren</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post(`http://localhost/todo/Backend/LogIn/LogIn.php`, { email: this.email, password: this.password });
        if (response.status === 200) {
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('user_id', response.data.user_id);
          location.reload();
        } else {
          alert('Anmeldung fehlgeschlagen');
        }
      } catch (error) {
        alert('Fehler bei der Anmeldung', error);
      }
    },
    async register() {
      const response = await axios.post(`http://localhost/todo/Backend/LogIn/Register.php`, { email: this.email, password: this.password });
      if(response.data.error === true){
        alert(response.data.errorMessage);
      }
      else{
        this.login();
      }
    },
    onEnter: function() {
        this.login();
    }
  }
};
</script>

<style scoped>
.login-container {
  font-family: 'Arial', sans-serif;
  background-color: #2d2d2d;
  color: #fff;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

.login-card {
  max-width: 400px;
  background-color: #333; 
  color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
}

.login-header {
  background-color: #3498db;
  color: #fff;
  padding: 10px;
  text-align: center;
  margin-bottom: 20px;
  border-radius: 8px 8px 0 0;
}

.login-form {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.form-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}
</style>

