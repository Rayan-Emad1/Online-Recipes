import React, { useState } from 'react';
import './styles.css';

const UserCard = ({ user, onFollow }) => {
  const token = localStorage.getItem('token');
  const [isFollowing, setIsFollowing] = useState(false);

  const handleFollow = async (followingId) => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/follow', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ following_id: followingId }),
      });

      const data = await response.json();
      console.log(data.message);
      setIsFollowing(true);

    } catch (error) {
      console.log('Error:', error);
    }
  };

  const handleUnfollow = async (followingId) => {
    try {

      const response = await fetch('http://127.0.0.1:8000/api/unfollow', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ following_id: followingId }),
      });

      const data = await response.json();
      console.log(data.message);

      setIsFollowing(false);

    } catch (error) {
      console.log('Error:', error);
    }
  };

  return (
    <div className="user-card">
      <span>{user.name}</span>
      {isFollowing ? (
        <button className="follow-button unfollow" onClick={() => handleUnfollow(user.id)}>
          Unfollow
        </button>
      ) : (
        <button className="follow-button" onClick={() => handleFollow(user.id)}>
          Follow
        </button>
      )}
    </div>
  );
};

export default UserCard;
