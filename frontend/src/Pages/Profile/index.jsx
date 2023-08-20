import React from 'react';
import Header from '../../Components/Header';
import UserProfileInfo from '../../Components/UserProfileInfo';
import RecipesContainer from '../../Components/RecipesContainer'
 
const Profile = () => {
  return (
    <>
      <Header />
      <UserProfileInfo />
      <RecipesContainer url = {'/posts/personal'} />
    </>
  );
};

export default Profile;
