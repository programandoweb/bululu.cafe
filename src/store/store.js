import { configureStore } from "@reduxjs/toolkit";

//import { Slice as formInputsSlice } from "./Slices/formInputsSlice";

import errorReducer from "./Slices/errorSlice";
import errorInputsSliceReducer from "./Slices/errorInputsSlice"; 
import userReducer from "./Slices/userSlice";
import dataSlice from "./Slices/dataSlice";
import dialogMessagesSlice from "./Slices/dialogMessagesSlice";
import shoppingCartSlice from "./Slices/shoppingCartSlice";
import snackbarSlice from "./Slices/snackbarSlice";
import loadingSlice from "./Slices/loadingSlice";



export default configureStore({

  reducer: {
    data:dataSlice,
    snackbar: snackbarSlice,
    shoppingCart:shoppingCartSlice,
    error: errorReducer,
    errorInputs:errorInputsSliceReducer,
    user:userReducer,
    dialog:dialogMessagesSlice,    
    loading:loadingSlice,
  },

});



