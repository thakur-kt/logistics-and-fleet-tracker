import { loadIcon } from '@/utils/lazyIcon'

export const navItems = [
  {
    label: 'Dashboard',
    to: '/',
    icon: loadIcon('Home'),
    roles: ['admin', 'user']
  },
  {
    label: 'Users',
    icon: loadIcon('Users'),
    suffixIcon: loadIcon('ChevronRight'),
    roles: ['admin', 'user'],
    children: [
      { label: 'All Users', to: '/users',roles: ['admin', 'user'] },
      { label: 'Roles', to: '/roles' ,roles: ['admin', 'user']},
      { label: 'Permissions', to: '/permissions',roles: ['admin', 'user'] },
    ],
  },
  {
    label: 'Settings',
    to: '/settings',
    icon: loadIcon('Settings'),
    roles: ['admin', 'user']
  }
]
