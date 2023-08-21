import React, { useState, useEffect } from 'react';
import Header from '../../Components/Header';
import UserProfileInfo from '../../Components/UserProfileInfo';
import RecipesContainer from '../../Components/RecipesContainer';
import './styles.css';

const Profile = () => {
  const [personalButton, setPersonalButton] = useState(true);
  const [refreshRecipes, setRefreshRecipes] = useState(false);
  

  useEffect(() => {
    if (refreshRecipes) {
      setRefreshRecipes(false);
    }
  }, []);

  return (
    <>
      <Header />
      <UserProfileInfo />

      <div className="button-container-profile">
        <button className={personalButton ? 'active' : ''} onClick={() => setPersonalButton(true)}>
          Personal Recipes
        </button>
        <button className={!personalButton ? 'active' : ''} onClick={() => setPersonalButton(false)}>
          List Recipes
        </button>
      </div>

      <RecipesContainer
        url={'/personal_info'}
        islist={!personalButton}
        refreshRecipes={() => setRefreshRecipes(true)}
      />
    </>
  );
};

export default Profile;