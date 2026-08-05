export function useLocalStorage() {
  const STORAGE_KEY = 'rencontre_search_filters'

  const storeFilters = (filters) => {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(filters))
    } catch (error) {
      console.error('Error storing filters:', error)
    }
  }

  const getStoredFilters = () => {
    try {
      const stored = localStorage.getItem(STORAGE_KEY)
      return stored ? JSON.parse(stored) : null
    } catch (error) {
      console.error('Error retrieving filters:', error)
      return null
    }
  }

  const clearStoredFilters = () => {
    try {
      localStorage.removeItem(STORAGE_KEY)
    } catch (error) {
      console.error('Error clearing filters:', error)
    }
  }

  return {
    storeFilters,
    getStoredFilters,
    clearStoredFilters,
  }
}
