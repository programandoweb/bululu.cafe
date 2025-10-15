import { useDispatch, useSelector } from 'react-redux';
import { addToFavorites, removeFromFavorites, updateQuantity } from '@/store/Slices/shoppingCartSlice';


export const useShoppingCart = () => {
  const dispatch        =   useDispatch();
  const shoppingCart    =   useSelector((state:any) => state.shoppingCart);

  const addToFavoritesHandler = (id: number) => {
    dispatch(addToFavorites({ id }));
  };

  const removeFromFavoritesHandler = (id: number) => {
    dispatch(removeFromFavorites({ id }));
  };

  const updateQuantityHandler = (id: number, quantity: number) => {
    dispatch(updateQuantity({ id, quantity }));
  };

  const isFavorite = (id: number) => {
    return shoppingCart.favorites[id] === true;
  };

  const getQuantity = (id: number) => {
    return shoppingCart.quantities[id] || 0;
  };

  return {
    addToFavorites: addToFavoritesHandler,
    removeFromFavorites: removeFromFavoritesHandler,
    updateQuantity: updateQuantityHandler,
    isFavorite,
    getQuantity,
  };
};
