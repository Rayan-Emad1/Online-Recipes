import React, { useState } from 'react';
import './styles.css'


const Searchbar = ({setSearchResults , setShowModal}) => {
    const [searchQuery, setSearchQuery] = useState('');
    const token = localStorage.getItem('token');

    const handleSearch = async () => {
      try {    
        if (!token) {
          console.log('Token not found.');
          return;
        }
    
        const response = await fetch('http://127.0.0.1:8000/api/search_user', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({ query: searchQuery }),
        });
    
        const data = await response.json();
        setSearchResults(data.users);
        setShowModal(true);
      } catch (error) {
        console.log('Error:', error);
      }
    };

    return<>
        <div className="search-bar">
          <input className='search-bar-input' type="text" value={searchQuery} onChange={(e) => setSearchQuery(e.target.value)} placeholder="Search users..." />
          <button className="search-button" onClick={handleSearch}>Search</button>
        </div>
    </>
    
}

export default Searchbar