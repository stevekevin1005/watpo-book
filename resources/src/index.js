import React from "react";
import ReactDOM from "react-dom";
import App from "./components/App";
import i18n from './i18n';
// set up redux
import {Provider} from "react-redux";
import store from "./store";
import { I18nextProvider } from 'react-i18next';

require("./assets/stylesheets/style.sass");

class Index extends React.Component{
  constructor(props){
    super(props);
  }
  render(){
    return(
      <Provider store={store}>
        <I18nextProvider i18n={ i18n }>
          <App/>
        </I18nextProvider>
      </Provider>);
  }
}

ReactDOM.render(<Index/>,document.querySelector("#container"));
