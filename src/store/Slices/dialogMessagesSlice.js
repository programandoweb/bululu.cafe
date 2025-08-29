import { createSlice } from "@reduxjs/toolkit";

export const dialogMessagesSlice = createSlice({
  name: 'dialog',
  initialState: {
    openSC:null,
    data:{},
    title: null,
    open: null,
    message: null,
    list: [],
    accept:null,
    size:"md:size-auto",
    openDrawer:false
  },
  reducers: {
    setOpenDrawer: (state, action) => {
      return { ...state, openDrawer: action.payload };
    },
    setOpenSC: (state, action) => {
      return { ...state, openSC: action.payload };
    },
    handleCloseModal: (state) => {
      return { ...state, open: null };
    },
    setShowModal: (state, action) => {
      return { ...state, open: action.payload };
    },
    setAcceptModal: (state, action) => {
      return { ...state, accept: action.payload };
    },
    setDialogMessage: (state, action) => {
      return { ...state, message: action.payload };
    },
    setDialogList: (state, action) => {
      return { ...state, list: action.payload };
    },
    clearDialogMessage: (state) => {
      return { ...state, items: null };
    },
  },
});

export const {setOpenDrawer, setOpenSC, setDialogMessage, clearDialogMessage, handleCloseModal, setShowModal, setDialogList, setAcceptModal } = dialogMessagesSlice.actions;

export default dialogMessagesSlice.reducer;
