import { createSlice } from "@reduxjs/toolkit";

export const userSlice = createSlice({
  name: 'user',
  initialState: {
    session: null,
  },
  reducers: {
    setSession: (state, action) => {
      return { ...state, session: action.payload };
    },
    clearSession: (state) => {
      return { ...state, session: null };
    },
  },
});

export const { setSession, clearSession } = userSlice.actions;

export default userSlice.reducer;
