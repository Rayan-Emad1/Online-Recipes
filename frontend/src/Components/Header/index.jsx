import React, { useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import SearchModal from '../SearchModel';
import Searchbar from '../Searchbar';
import './styles.css';

const Header = ({setSearchRecipe}) => {
  const [searchResults, setSearchResults] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();
  
  const closeSearchModal = () => {
    setShowModal(false);
    setSearchResults([]);
  };

  const OpenProfile = () => {
    navigate('/profile');
  }

  const Logout = () => {
    navigate('/');
  }
  
  const goHome = () => {
    if(mainSearch) {setSearchRecipe([])};
    navigate('/main');
  }
  
  const mainSearch = location.pathname === '/main';

  return (
    <header className="header-container">
      <div className="header-content">
        <div className="logo" onClick={goHome}>Online Recipes</div>
        {mainSearch ? (
          <Searchbar setSearchResults={setSearchResults} mainSearch={mainSearch} setSearchRecipe={setSearchRecipe} />
        ) : (
          <Searchbar setSearchResults={setSearchResults} setShowModal={setShowModal} mainSearch={mainSearch} />
        )}
        <div>
          <img onClick={Logout} className="logout-image" src="https://icon-library.com/images/logout-icon-transparent/logout-icon-transparent-7.jpg" />
          <img onClick={OpenProfile} className="profile-image" src="https://w7.pngwing.com/pngs/128/223/png-transparent-user-person-profile-instagram-ui-colored-icon.png" />
        </div>
      </div>
      {showModal && !mainSearch && (<SearchModal searchResults={searchResults} closeModal={closeSearchModal}  />)}
    </header>
  );
};

export default Header;
