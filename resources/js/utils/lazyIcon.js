import { defineAsyncComponent } from 'vue'

// Function to dynamically load an icon component by name
export const loadIcon = (iconName) => {
  // Use Vue's defineAsyncComponent for lazy loading
  // Dynamically import the icon from lucide-vue-next by its name
  return defineAsyncComponent(() =>
    import('lucide-vue-next').then(module => module[iconName])
  )
}