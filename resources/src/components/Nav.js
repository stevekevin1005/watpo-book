// 導覽列

import { LinkContainer } from 'react-router-bootstrap';
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import i18next from 'i18next';

const Navbar = ReactBootstrap.Navbar,
      Nav_ = ReactBootstrap.Nav,
      NavItem = ReactBootstrap.NavItem,
      NavDropdown = ReactBootstrap.NavDropdown,
      MenuItem = ReactBootstrap.MenuItem;

class Nav extends React.Component{
    constructor(props){
        super(props);
        this.changeLang = this.changeLang.bind(this);
    }
    changeLang(lang){
      i18next.changeLanguage(lang);
    }
    render(){
        const { t } = this.props;

        return(
          <Navbar inverse collapseOnSelect>
          <Navbar.Header>
            <Navbar.Brand>
              <LinkContainer to="/">
              <span>泰和殿 Wat Po</span>
              </LinkContainer>
            </Navbar.Brand>
            <Navbar.Toggle />
          </Navbar.Header>
          <Navbar.Collapse>
            <Nav_>
              <LinkContainer to="/reservation/0" key="reservation_title">
                <NavItem>
                  {t("book")}
                </NavItem>
              </LinkContainer>
              <LinkContainer to="/book/check/0" key="book_check_title">
                <NavItem>
                  {t("book check")}
                </NavItem>
              </LinkContainer>
            </Nav_>
            <Nav_ pullRight>
              <NavDropdown title={t("lang")} id="basic-nav-dropdown">
                <MenuItem onClick={()=>{this.changeLang("zh");}} >中文</MenuItem>
                <MenuItem onClick={()=>{this.changeLang("jp");}} >日本語</MenuItem>
                <MenuItem onClick={()=>{this.changeLang("en");}} >English</MenuItem>
              </NavDropdown>
            </Nav_>
          </Navbar.Collapse>
        </Navbar>
        );
    }
}

module.exports = translate()(Nav);