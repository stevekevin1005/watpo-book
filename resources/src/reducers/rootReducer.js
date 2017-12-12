import reducers from "./reducers";
import { combineReducers } from "redux";

const rootReducer = combineReducers({
    currentUser: reducers.currentUser,
    currentRoom: reducers.currentRoom,
    lastAction: reducers.lastAction,
    loading: reducers.loading
  });

  module.exports = rootReducer;