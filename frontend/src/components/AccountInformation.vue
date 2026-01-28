<template>
  <div>
    <h3>Step 1 — Account Information</h3>
    <form @submit.prevent="next">
      <div class="mb-3">
        <label class="form-label">First Name</label>
        <input v-model="form.first_name" class="form-control" />
        <div class="text-danger" v-if="displayErrors.first_name">{{ displayErrors.first_name }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input v-model="form.last_name" class="form-control" />
        <div class="text-danger" v-if="displayErrors.last_name">{{ displayErrors.last_name }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input v-model="form.email" type="email" class="form-control" />
        <div class="text-danger" v-if="displayErrors.email">{{ displayErrors.email }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input v-model="form.username" class="form-control" />
        <div class="text-danger" v-if="displayErrors.username">{{ displayErrors.username }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input :type="show ? 'text' : 'password'" v-model="form.password" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input :type="show ? 'text' : 'password'" v-model="form.password_confirmation" class="form-control" />
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" v-model="show" id="showPass" />
          <label class="form-check-label" for="showPass">Show Password</label>
        </div>
        <div class="text-danger" v-if="displayErrors.password">{{ displayErrors.password }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Type of Participation</label>
        <select v-model="form.type_of_participation" class="form-select">
          <option disabled value="">Select...</option>
          <option>Buyer</option>
          <option>Exhibitor</option>
          <option>Visitor</option>
        </select>
        <div class="text-danger" v-if="displayErrors.type_of_participation">{{ displayErrors.type_of_participation }}</div>
      </div>

      <button class="btn btn-primary">Next</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, watch, ref, computed } from 'vue'
import { validateAccount } from '../validation'
const props = defineProps({ modelValue: Object, errors: Object })
const emit = defineEmits(['update:modelValue', 'next', 'validation-errors'])

const show = ref(false)

const form = reactive({
  first_name: '', last_name: '', email: '', username: '', password: '', password_confirmation: '', type_of_participation: ''
})

const localErrors = ref({})

if (props.modelValue && props.modelValue.account) {
  Object.assign(form, props.modelValue.account)
}

watch(() => props.modelValue, (v) => {
  if (v && v.account) Object.assign(form, v.account)
})

const displayErrors = computed(() => ({ ...(props.errors || {}), ...(localErrors.value || {}) }))

function next() {
  const v = validateAccount(form)
  if (Object.keys(v).length) {
    localErrors.value = v
    emit('validation-errors', v)
    return
  }
  localErrors.value = {}
  emit('validation-errors', {})
  emit('update:modelValue', { account: { ...form } })
  emit('next')
}
</script>

<style scoped></style>
