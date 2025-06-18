<template>
  <div class="max-w-md mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <form @submit.prevent="login">
      <div class="mb-4">
        <label class="block mb-1" for="email">Email</label>
        <input
          v-model="form.email"
          id="email"
          type="email"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
      <div class="mb-4">
        <label class="block mb-1" for="password">Password</label>
        <input
          v-model="form.password"
          id="password"
          type="password"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Login
      </button>
    </form>
    <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import axios from "axios";

const form = reactive({
  email: "",
  password: "",
});

const error = ref("");

const login = async () => {
  error.value = "";
  try {
    const response = await axios.post("http://localhost:8000/api/login", form);
    localStorage.setItem("token", response.data.access_token);
    alert("Login successful!");
  } catch (err) {
    error.value = err.response?.data?.message || "Login failed.";
  }
};
</script>

<style scoped></style>
