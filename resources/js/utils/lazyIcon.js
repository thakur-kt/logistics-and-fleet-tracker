import { defineAsyncComponent } from 'vue'

export const loadIcon = (iconName) => {
  return defineAsyncComponent(() =>
    import('lucide-vue-next').then(module => module[iconName])
  )
}