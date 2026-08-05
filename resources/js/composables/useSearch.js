export function useSearch() {
  const performSearch = async (filters) => {
    try {
      const params = new URLSearchParams()

      // Add all filter parameters
      Object.entries(filters).forEach(([key, value]) => {
        if (value && value !== '') {
          if (Array.isArray(value)) {
            value.forEach((v) => params.append(`${key}[]`, v))
          } else {
            params.append(key, value)
          }
        }
      })

      const response = await fetch(`/api/search?${params.toString()}`)
      const data = await response.json()

      if (data.success) {
        return data
      } else {
        throw new Error(data.message || 'Search failed')
      }
    } catch (error) {
      console.error('Search error:', error)
      throw error
    }
  }

  const fetchFilterOptions = async () => {
    try {
      const response = await fetch('/api/search/filters')
      const data = await response.json()
      if (data.success) {
        return data.data
      }
    } catch (error) {
      console.error('Error fetching filter options:', error)
    }
  }

  const fetchSavedSearches = async () => {
    try {
      const response = await fetch('/api/saved-searches')
      const data = await response.json()
      if (data.success) {
        return data.data
      }
    } catch (error) {
      console.error('Error fetching saved searches:', error)
    }
  }

  const getRecommendations = async () => {
    try {
      const response = await fetch('/api/search/recommendations')
      const data = await response.json()
      if (data.success) {
        return data.data
      }
    } catch (error) {
      console.error('Error fetching recommendations:', error)
    }
  }

  return {
    performSearch,
    fetchFilterOptions,
    fetchSavedSearches,
    getRecommendations,
  }
}
