<template>
   <div class="max-w-lg mx-auto p-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Driver Profile</h2>
    <Form 
      :validation-schema="schema" 
      @submit="onSubmit"
      v-slot="{ resetForm }"
      class="space-y-6"
    >
    <div>
        <label for="name" class="block mb-1 font-semibold">Name</label>
        <Field
          id="name"
          name="name"
          class="input w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-royalblue"
          placeholder="Enter full name"
        />
        <ErrorMessage name="name" class="text-red-500 text-sm mt-1" />
      </div>
      <div>
        <label for="phone" class="block mb-1 font-semibold">Phone</label>
        <Field
          id="phone"
          name="phone"
          class="input w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-royalblue"
          placeholder="Enter phone number"
        />
        <ErrorMessage name="phone" class="text-red-500 text-sm mt-1" />
      </div>

      <div>
        <label for="vehicle_number" class="block mb-1 font-semibold">Vehicle Number</label>
        <Field
          id="vehicle_number"
          name="vehicle_number"
          class="input w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-royalblue"
          placeholder="Enter vehicle number"
        />
        <ErrorMessage name="vehicle_number" class="text-red-500 text-sm mt-1" />
      </div>
      <button type="submit" class="btn w-full py-3 bg-royalblue text-white font-bold rounded hover:bg-blue-700 transition">Save</button>

      <!-- Use a watcher or onMounted here inside the template scope -->
      <ResetFormHelper :resetForm="resetForm" />
    </Form>
  </div>
</template>

<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useDriverStore } from '@/stores/driver';
import { onMounted, watch } from 'vue';

const driverStore = useDriverStore();

const schema = yup.object({
  name: yup.string().required('Name is required').max(255),
  phone: yup.string().nullable().matches(/^[0-9+\-()\s]*$/, 'Phone must be valid'),
  vehicle_number: yup.string().nullable().max(20),
});

const onSubmit = async (values) => {
  await driverStore.updateProfile(values);
  alert('✅ Profile updated successfully');
};

// Create a small helper component to call resetForm with data
const ResetFormHelper = {
  props: ['resetForm'],
  setup(props) {
    onMounted(async () => {
      await driverStore.fetchProfile();
      props.resetForm({
        values: {
          name: driverStore.profile.name || '',
          phone: driverStore.profile.phone || '',
          vehicle_number: driverStore.profile.vehicle_number || '',
        },
      });
    });

    watch(() => driverStore.profile, (newProfile) => {
      if (newProfile) {
        props.resetForm({
          values: {
            name: newProfile.name || '',
            phone: newProfile.phone || '',
            vehicle_number: newProfile.vehicle_number || '',
          },
        });
      }
    });

    return () => null; // render nothing
  },
};
</script>

<style scoped>
.input {
  /* fallback styles if Tailwind is not available */
  font-size: 1rem;
}

.btn {
  background-color: royalblue;
}

.btn:hover {
  background-color: #1e40af; /* Tailwind blue-700 */
}

@media (min-width: 640px) {
  /* For larger screens, maybe max-width */
  .max-w-lg {
    max-width: 32rem; /* 512px */
  }
}
</style>