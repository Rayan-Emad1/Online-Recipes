import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import SearchModal from '../SearchModel';
import Searchbar from '../Searchbar';
import './styles.css';

const Header = () => {
  const [searchResults, setSearchResults] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const navigate = useNavigate();
  
  const closeSearchModal = () => {
    setShowModal(false);
    setSearchResults([]);
  };

  const OpenProfile = () => {
    navigate('/profile')
  }
  
  const goHome = () => {
    navigate('/main')
  }
  

  return (
    <header className="header-container">

      <div className="header-content">

        <div className="logo" onClick={goHome}>Online Recipes</div>
        <Searchbar setSearchResults={setSearchResults} setShowModal={setShowModal} />
        <img onClick={OpenProfile} className="profile-image" src="https://w7.pngwing.com/pngs/128/223/png-transparent-user-person-profile-instagram-ui-colored-icon.png" />

      </div>

      {showModal && (<SearchModal searchResults={searchResults} closeModal={closeSearchModal}  />)}
    </header>
  );
};

export default Header;
