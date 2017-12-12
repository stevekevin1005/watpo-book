import React from "react";
import ReactDOM from "react-dom";
import App from "./components/App";

// set up redux
import {Provider} from "react-redux";
import store from "./store";

require("./assets/stylesheets/style.sass");

const Index = (props)=>(
  <Provider store={store}>
      <App/>
  </Provider>      
);

ReactDOM.render(<Index/>,document.querySelector("#container"));
