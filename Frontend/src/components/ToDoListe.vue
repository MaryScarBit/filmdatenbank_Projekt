<template>
  <div>
    <div class="header">
      <h2>ToDo-Liste</h2>
    </div>

    <div class="add-todo">
      <label for="newTodo">Neues To-Do:</label>&nbsp;
      <input id="newTodo" v-model="newTodo.text" v-on:keyup.enter="onEnter"/>
      <button @click="addTodo">Hinzufügen</button>
    </div>

    <div class="todo-list">
      <h3>Offene To-Dos:</h3>
      <ul v-if="todoList.length > 0" class="list">
        <li v-for="todo in todoList" :key="todo.id" class="list-item">
          <template v-if="editingTodo !== todo.id">
            <span class="todo-text">#{{ todo.id }} - {{ todo.text }}</span>
            <button @click="editTodoStart(todo.id)" class="edit-btn">Bearbeiten</button>
          </template>
          <template v-else>
            <input v-model="todo.text"/>
            <button @click="editTodoSubmit(todo)" class="edit-btn">Speichern</button>
            <button @click="cancelEdit" class="edit-btn">Abbrechen</button>
          </template>
          <button @click="deleteTodo(todo.id)" class="delete-btn">Löschen</button>
        </li>
      </ul>
      <p v-else class="no-todos">Keine offenen To-Dos vorhanden</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      newTodo: { text: '' },
      todoList: [],
      editingTodo: null,
      editText: '',
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`http://localhost/todo/Backend/ToDoList/api.php?token=${localStorage.getItem('token')}&user_id=${localStorage.getItem('user_id')}`);
        this.todoList = response.data;
      } catch (error) {
        console.error('Fehler beim Abrufen der Daten:', error);
      }
    },
    async addTodo() {
      try {
        await axios.post(`http://localhost/todo/Backend/ToDoList/api.php?token=${localStorage.getItem('token')}&user_id=${localStorage.getItem('user_id')}`, { text: this.newTodo.text });
        this.newTodo.text = '';
        this.fetchData();
      } catch (error) {
        console.error('Fehler beim Hinzufügen der Aufgabe:', error);
      }
    },
    onEnter: function() {
        this.addTodo();
    },
    editTodoStart(id) {
      const todo = this.todoList.find(item => item.id === id);
      this.editingTodo = id;
      this.editText = todo.text;
    },
    async editTodoSubmit(todo) {
      try {
        await axios.put(`http://localhost/todo/Backend/ToDoList/api.php?token=${localStorage.getItem('token')}&user_id=${localStorage.getItem('user_id')}&id=${todo.id}`, { text: todo.text })
          .then((response) => {
            console.log(response);
          }, (error) => {
            console.error('Fehler beim Hinzufügen der Aufgabe:', error);
          });
        this.fetchData();
        this.editingTodo = null;
      } catch (error) {
        console.error('Fehler beim Bearbeiten der Aufgabe:', error);
      }
    },
    cancelEdit() {
      this.editingTodo = null;
    },
    async deleteTodo(id) {
      try {
        await axios.delete(`http://localhost/todo/Backend/ToDoList/api.php?token=${localStorage.getItem('token')}&user_id=${localStorage.getItem('user_id')}&id=${id}`);
        this.fetchData();
      } catch (error) {
        console.error('Fehler beim Löschen der Aufgabe:', error);
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 600px;
  background-color: #333;
  color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
}

.header {
  background-color: #333;
  color: #fff;
  padding: 10px;
  text-align: center;
  margin-bottom: 20px;
  border-radius: 8px 8px 0 0;
}

.add-todo {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

.todo-list {
  text-align: center;
}

.list {
  list-style: none;
  padding: 0;
}

.list-item {
  background-color: #444;
  padding: 10px;
  margin-bottom: 10px;
  box-shadow: 0 0 5px rgba(255, 255, 255, 0.1);
  border-radius: 4px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.todo-text {
  margin-right: 10px;
  color: #fff;
}

.edit-btn {
  background-color: #3498db;
  color: #fff;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 4px;
}

.delete-btn {
  background-color: #e74c3c;
  color: #fff;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 4px;
  margin-left: 5px;
}

.cancel-btn {
  background-color: #e74c3c;
}
</style>