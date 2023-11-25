<template>
  <div id="app" class="container">
    <div v-if="isAuthenticated">
      <button @click="logout" class="logout-btn">Logout</button>
      <ToDoListe />
    </div>
    <div v-else>
      <LogIn @login-success="setAuthenticated" />
    </div>
  </div>
</template>

<script>
import ToDoListe from './components/ToDoListe.vue';
import LogIn from './components/LogIn.vue';
import axios from 'axios';

export default {
  components: {
    ToDoListe,
    LogIn
  },
  data() {
    return {
      isAuthenticated: false,
      userId: ''
    };
  },
  methods: {
    async checkAuthentication() {
      try {
        if (localStorage.getItem('token') === null) {
          this.isAuthenticated = false;
        } else {
          const response = await axios.put(`http://localhost/todo/Backend/LogIn/IsAuthorized.php?token=${localStorage.getItem('token')}&user_id=${localStorage.getItem('user_id')}`);
          
          if (response.status === 200) {
            this.isAuthenticated = true;
          } else {
            this.isAuthenticated = false;
          }
        }
      } catch {
        this.isAuthenticated = false;
      }
    },
    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user_id');
      this.isAuthenticated = false;
      location.reload();
    },
    setAuthenticated() {
      this.isAuthenticated = true;
    },
    async createTables() {
      await axios.put(`http://localhost/todo/Backend/General/CreateTables.php`);
    }
  },
  mounted() {
    this.checkAuthentication();
    this.createTables();
  }
};
</script>

<style>
body {
  font-family: 'Arial', sans-serif;
  background-color: #2d2d2d;
  color: #fff;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

label {
  font-size: 14px;
}

input {
  padding: 8px;
  flex-grow: 1;
  background-color: #444;
  color: #fff;
  border: 1px solid #555;
  border-radius: 4px;
}

button {
  padding: 8px 16px;
  background-color: #444;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s;
  margin-top: 2px;
}

button:hover {
  background-color: #27ae60;
}

.logout-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: #e74c3c;
  color: #fff;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 4px;
}
</style>