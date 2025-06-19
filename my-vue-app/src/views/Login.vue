<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 py-12 px-4"
  >
    <div class="w-full max-w-md">
      <div class="auth-card">
        <div class="text-center mb-8">
          <div class="auth-icon bg-gradient-to-r from-blue-400 to-purple-400">
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
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
              ></path>
            </svg>
          </div>
          <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
          <p class="text-white/70">Sign in to your account</p>
        </div>

        <form @submit.prevent="login" class="space-y-6">
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
              placeholder="Enter your password"
            />
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" class="form-checkbox" />
              <span class="ml-2 text-sm text-white/70">Remember me</span>
            </label>
            <a
              href="#"
              class="text-sm text-blue-300 hover:text-blue-200 transition-colors"
            >
              Forgot password?
            </a>
          </div>

          <button type="submit" class="btn-primary">Sign In</button>
        </form>

        <div v-if="error" class="error-message">
          <p class="text-red-200 text-sm">{{ error }}</p>
        </div>

        <div class="mt-6 text-center">
          <p class="text-white/70 text-sm">
            Don't have an account?
            <router-link
              to="/register"
              class="text-blue-300 hover:text-blue-200 font-medium transition-colors"
            >
              Sign up
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
  email: "",
  password: "",
});

const error = ref("");

const login = async () => {
  error.value = "";
  try {
    const response = await axios.post("http://localhost:8000/api/login", form);
    localStorage.setItem("token", response.data.access_token);
    emit("auth-success");
  } catch (err) {
    console.log(err);
    error.value = err.response?.data?.message || "Login failed.";
  }
};
</script>
