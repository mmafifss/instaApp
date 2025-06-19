<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-800 py-12 px-4"
  >
    <div class="w-full max-w-md">
      <div class="auth-card">
        <div class="text-center mb-8">
          <div class="auth-icon bg-gradient-to-r from-purple-400 to-pink-400">
            <svg
              class="w-8 h-8 text-white"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
              ></path>
            </svg>
          </div>
          <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
          <p class="text-white/70">Join our community today</p>
        </div>

        <form @submit.prevent="register" class="space-y-6">
          <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input
              v-model="form.name"
              id="name"
              type="text"
              required
              class="form-input"
              placeholder="Enter your full name"
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input
              v-model="form.email"
              id="email"
              type="email"
              required
              class="form-input"
              placeholder="Enter your email"
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input
              v-model="form.password"
              id="password"
              type="password"
              required
              class="form-input"
              placeholder="Create a password"
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="password_confirmation"
              >Confirm Password</label
            >
            <input
              v-model="form.password_confirmation"
              id="password_confirmation"
              type="password"
              required
              class="form-input"
              placeholder="Confirm your password"
            />
          </div>

          <button type="submit" class="btn-primary">Create Account</button>
        </form>

        <div v-if="error" class="error-message">
          <p class="text-red-200 text-sm">{{ error }}</p>
        </div>

        <div class="mt-6 text-center">
          <p class="text-white/70 text-sm">
            Already have an account?
            <router-link
              to="/login"
              class="text-purple-300 hover:text-purple-200 font-medium transition-colors"
            >
              Sign in
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const emit = defineEmits(["auth-success"]);

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
    router.push("/login");
  } catch (err) {
    error.value = err.response?.data?.message || "Registration failed.";
  }
};
</script>
