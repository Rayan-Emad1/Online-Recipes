import React, { useState, useEffect } from 'react';
import './styles.css';

const UserCard = ({ user }) => {
  const token = localStorage.getItem('token');
  const [isFollowing, setIsFollowing] = useState(user.is_following);

  const handleFollow = async (followingId) => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/toggle_follow', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ following_id: followingId }),
      });

      const data = await response.json();
      console.log(data.message);
      setIsFollowing(data.is_following);

    } catch (error) {
      console.log('Error:', error);
    }
  };

  useEffect(() => {
    setIsFollowing(user.is_following);
  }, [user.is_following]);

  return (
    <div className="user-card">
      <span>{user.name}</span>
      <button className={`follow-button ${isFollowing ? 'unfollow' : ''}`} onClick={() => handleFollow(user.id)}>
        {isFollowing ? 'Unfollow' : 'Follow'}
      </button>
    </div>
  );
};

export default UserCard;
