import React from 'react';
import UserCard from '../UserCard';
import './styles.css'
const SearchModal = ({ searchResults, closeModal }) => { 
  return (
    <div className="search-modal">
      <div className="modal-content">
        <h4 onClick={closeModal} className="close-info-model"> X </h4>
        <div className="search-results">
          {searchResults.map(user => (
            <UserCard key={user.id} user={user} /> 
          ))}
        </div>
        
      </div>
    </div>
  );
};

export default SearchModal;
