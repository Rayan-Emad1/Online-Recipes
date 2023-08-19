import React from 'react';
import UserCard from '../UserCard';
import './styles.css'
const SearchModal = ({ searchResults, closeModal }) => { 
  return (
    <div className="search-modal">
      <div className="modal-content">
        <div className="search-results">
          {searchResults.map(user => (
            <UserCard key={user.id} user={user} /> 
          ))}
        </div>
        <button onClick={closeModal} className="close-search-button"> Close </button>
      </div>
    </div>
  );
};

export default SearchModal;
