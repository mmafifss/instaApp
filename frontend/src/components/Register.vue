<template>
  <div class="max-w-md mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form @submit.prevent="register">
      <div class="mb-4">
        <label class="block mb-1" for="name">Name</label>
        <input
          v-model="form.name"
          id="name"
          type="text"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
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
      <div class="mb-4">
        <label class="block mb-1" for="password_confirmation"
          >Confirm Password</label
        >
        <input
          v-model="form.password_confirmation"
          id="password_confirmation"
          type="password"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Register
      </button>
    </form>
    <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import axios from "axios";

const form = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const error = ref("");

const register = async () => {
  error.value = "";
  try {
    await axios.post("http://localhost:8000/api/register", form);
    alert("Registration successful! Please login.");
  } catch (err) {
    error.value = err.response?.data?.message || "Registration failed.";
  }
};
</script>

<style scoped></style>
