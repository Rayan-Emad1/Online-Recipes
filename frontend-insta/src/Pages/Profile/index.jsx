import React from 'react';
import Header from '../../Components/Header';
import UserProfileInfo from '../../Components/UserProfileInfo'; // Adjust the path based on your folder structure
import PostContainer from '../../Components/PostsContainer'
 
const Profile = () => {
  return (
    <>
      <Header />
      <UserProfileInfo />
      <PostContainer url = {'/posts/personal'} />
    </>
  );
};

export default Profile;
