/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoovle *  
 * ---------------------------------------------------
 */

import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface ShoppingCartState {
  favorites: { [id: number]: boolean };
  quantities: { [id: number]: number };
  products: any[];
  whatsapp: string;
  variantQuantities: { [variantId: string]: number };
  total: number; // Total acumulado de la compra
}

const loadStateFromLocalStorage = (): ShoppingCartState => {
  try {
    const storedVariantQuantities = localStorage.getItem("variantQuantities");
    return {
      favorites: {},
      quantities: {},
      products: [],
      whatsapp: '',
      variantQuantities: storedVariantQuantities ? JSON.parse(storedVariantQuantities) : {},
      total: 0,
    };
  } catch (error) {
    console.error("Error loading variant quantities from localStorage:", error);
    return {
      favorites: {},
      quantities: {},
      products: [],
      whatsapp: '',
      variantQuantities: {},
      total: 0,
    };
  }
};

const initialState: ShoppingCartState = loadStateFromLocalStorage();

const shoppingCartSlice = createSlice({
  name: 'shoppingCart',
  initialState,
  reducers: {
    setTotal(state, action: PayloadAction<number>) {
      state.total = action.payload;
    },
    updateFavorites(state, action: any) {
      state.favorites = action.payload;
    },
    addToFavorites(state, action: PayloadAction<{ id: number }>) {
      const { id } = action.payload;
      state.favorites[id] = true;
    },
    removeFromFavorites(state, action: PayloadAction<{ id: number }>) {
      const { id } = action.payload;
      delete state.favorites[id];
    },
    updateQuantity(state, action: PayloadAction<{ id: number; quantity: number }>) {
      const { id, quantity } = action.payload;
      state.quantities[id] = quantity;
    },
    setQuantity(state, action: any) {
      state.quantities = action.payload;
    },
    removeProduct(state, action: PayloadAction<{ id: number }>) {
      const { id } = action.payload;
      delete state.quantities[id];
      delete state.favorites[id];
      Object.keys(state.variantQuantities).forEach((variantId) => {
        if (variantId.startsWith(`${id}-`)) {
          delete state.variantQuantities[variantId];
        }
      });
      localStorage.setItem("variantQuantities", JSON.stringify(state.variantQuantities));
      state.total = calculateTotal(state.variantQuantities, state.products);
    },
    setProducts(state, action: PayloadAction<{ products: any[], whatsapp: string }>) {
      state.products = action.payload.products;
      state.whatsapp = action.payload.whatsapp;
    },
    increaseVariantQuantity(state, action: PayloadAction<{ variantId: string }>) {
      const { variantId } = action.payload;
      state.variantQuantities[variantId] = (state.variantQuantities[variantId] || 0) + 1;
      localStorage.setItem("variantQuantities", JSON.stringify(state.variantQuantities));
      state.total = calculateTotal(state.variantQuantities, state.products);
    },
    decreaseVariantQuantity(state, action: PayloadAction<{ variantId: string }>) {
      const { variantId } = action.payload;
      const newQuantity = Math.max((state.variantQuantities[variantId] || 0) - 1, 0);
      if (newQuantity === 0) {
        delete state.variantQuantities[variantId];
      } else {
        state.variantQuantities[variantId] = newQuantity;
      }
      localStorage.setItem("variantQuantities", JSON.stringify(state.variantQuantities));
      state.total = calculateTotal(state.variantQuantities, state.products);
    },
    // Acción para limpiar el carrito al completar la orden
    clearCart(state) {
      state.favorites = {};
      state.quantities = {};
      state.products = [];
      state.whatsapp = '';
      state.variantQuantities = {};
      state.total = 0;
      localStorage.removeItem("variantQuantities");
      localStorage.removeItem("user");
      localStorage.removeItem("quantities");
    },
  },
});

// Función para calcular el total basado en variantQuantities y productos
const calculateTotal = (variantQuantities: { [variantId: string]: number }, products: any[]) => {
  return products.reduce((total, product) => {
    if (product.resume && product.resume.variantItems) {
      return total + product.resume.variantItems.reduce((sum: number, variant: any) => {
        const variantId = `${product.id}-${variant.id}`;
        const quantity = variantQuantities[variantId] || 0;
        return sum + quantity * variant.variant_price;
      }, 0);
    }
    return total;
  }, 0);
};

export const {
  addToFavorites,
  removeFromFavorites,
  updateQuantity,
  updateFavorites,
  setQuantity,
  removeProduct,
  setProducts,
  increaseVariantQuantity,
  decreaseVariantQuantity,
  setTotal,
  clearCart
} = shoppingCartSlice.actions;

export default shoppingCartSlice.reducer;
