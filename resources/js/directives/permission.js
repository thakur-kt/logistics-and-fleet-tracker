import { useAuthStore } from '@/stores/auth';

export const vHasRole = {
  mounted(el, binding) {
    const auth = useAuthStore();
    const requiredRoles = Array.isArray(binding.value)
      ? binding.value
      : [binding.value];

    if (!requiredRoles.some(role => auth.roles.includes(role))) {
      el.remove(); // or el.style.display = 'none'
    }
  },
};

export const vHasPermission = {
  mounted(el, binding) {
    const auth = useAuthStore();
    const requiredPermissions = Array.isArray(binding.value)
      ? binding.value
      : [binding.value];
    if (!requiredPermissions.some(p => auth.permissions.includes(p))) {
      el.remove(); // or el.style.display = 'none'
    }
  },
};
