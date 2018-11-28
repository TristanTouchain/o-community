/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { NavLink } from 'react-router-dom';
import ClassNames from 'classnames';
import AuthService from 'src/components/AuthService';
import decode from 'jwt-decode';
import {
  Collapse,
  Navbar,
  Nav,
  NavLink as ReactStrapLink,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownMenu,
  Badge,
  NavbarBrand,
  NavbarToggler,
  NavItem,
  DropdownItem,
} from 'reactstrap';

import './navbar.scss';

class ReactStrapNavbar extends React.Component {
  constructor(props) {
    super(props);
    this.toggle = this.toggle.bind(this);
    this.state = {
      isOpen: false,
      collapse: true,
    };
  }

  componentDidMount() {
    const { getConnectedMember } = this.props;
    if (this.Auth.getToken()) {
      getConnectedMember(this.Auth.getProfile().userId);
    }
  }

  // Fonction qui permet de récupérer un élément imbriqué dans un objet à plusieurs niveaux
  getNestedObject = (nestedObj, pathArr) => (
    pathArr.reduce((obj, key) => (obj && obj[key] !== 'undefined') ? obj[key] : undefined, nestedObj)
  );

  // Fonction qui permet de déconnecter le membre et de passer le isConnected à false
  disconnect() {
    this.Auth = new AuthService();
    this.Auth.logout();
    const { disconnectMember } = this.props;
    disconnectMember();
  }

  toggle() {
    const { isOpen } = this.state;
    console.log(!isOpen);
    
    this.setState({
      isOpen: !isOpen,
    });
  }

  toggleNavbar() {
    const { collapsed } = this.state;
    this.setState({
      collapsed: !collapsed,
    });
  }

  render() {
    const { isConnected, page } = this.props;
    // Les classes sont modifiées en fonction de la page courante
    const classcolor = ClassNames(
      { 'home-navfoot': page === '/' },
      { 'members-navfoot': page === '/members' },
      { 'projects-navfoot': page === '/projects' },
      { 'login-navfoot': page === '/login' },
      { 'member-navfoot': page.includes('/members/') },
      { 'project-navfoot': page.includes('/projects/') },
    );

    const classNavBar = (classcolor !== '')
      ? `fixed-top row bg-${classcolor}`
      : 'fixed-top row bg-notfound-navfoot';

    const classNavBarColor = (classcolor !== '')
      ? `bg-${classcolor}`
      : 'bg-notfound-navfoot';

    // On récupère les valeurs du state et des props dont on a besoin
    const { isOpen } = this.state;
    const { connectMember, disconnectMember, connectedMember } = this.props;
    const firstnameConnectedMember = localStorage.getItem('connectedMemberFirstName');
    const slugMemberConnectedMember = localStorage.getItem('connectedMemberSlugMember');
    const slugProjectConnectedMember = localStorage.getItem('connectedMemberSlugProject');

    // Si un token valide existe isConnected à true dans le state
    this.Auth = new AuthService();
    const token = this.Auth.getToken();
    if (token && !this.Auth.isTokenExpired(token)) {
      connectMember();
    }
    // Si le token n'est plus valide, on déconnecte l'utilisateur et on le redirige vers la page de connexion
    else if (this.Auth.isTokenExpired(token)) {
      disconnectMember();
      this.Auth.logout();
      window.location.replace('/login');
    }

    return (
      <div id="navbar">
        <Navbar className={classNavBar} dark expand="lg">
          <NavbarBrand className="mx-3">
            <NavLink activeClassName="" className=" my-1" exact to="/"><img src="/src/images/logo_oclock_community_navbar.png" alt="Logo Community" /></NavLink>
          </NavbarBrand>
          <NavbarToggler onClick={this.toggle} className="" />
          <Collapse isOpen={isOpen} navbar>
            <Nav className="ml-auto" navbar>
              <NavItem>
                <NavLink activeClassName="" className="nav-item nav-link text-white text-center text-uppercase font-weight-bold" exact to="/">Accueil</NavLink>
              </NavItem>
              <NavItem>
                <ReactStrapLink className="nav-item nav-link text-white text-center text-uppercase font-weight-bold" href="http://oclock.io">O'Clock</ReactStrapLink>
              </NavItem>
              <NavItem>
                <NavLink activeClassName="" className="nav-item nav-link text-white text-center text-uppercase font-weight-bold" exact to="/projects">Projets</NavLink>
              </NavItem>
              <NavItem>
                <NavLink activeClassName="" className="nav-item nav-link text-white text-center text-uppercase font-weight-bold" exact to="/members">Etudiants</NavLink>
              </NavItem>
              {/* Si le membre est connecté un menu dropdown s'affiche */}
              { (connectedMember !== undefined)
              && (
                <UncontrolledDropdown nav inNavbar>
                  <DropdownToggle nav caret className="nav-item nav-link text-white text-uppercase text-center font-weight-bold">
                    {`Bonjour ${firstnameConnectedMember}`}
                  </DropdownToggle>
                  <DropdownMenu right className={classNavBarColor}>
                    <DropdownItem divider />
                    <NavLink to={`/members/${slugMemberConnectedMember}`} exact className="nav-item nav-link text-center text-white text-uppercase font-weight-bold">
                      Mon profil
                    </NavLink>
                    <NavLink to={`/members/${slugMemberConnectedMember}/edit`} exact className="nav-item nav-link text-center text-white text-uppercase font-weight-bold">
                      Modifier mon profil
                    </NavLink>
                    {/* Si le membre n'a pas encore de projet, les liens pour y accéder ne s'affichent pas */}
                    { (slugProjectConnectedMember !== null)
                    && (
                      <div>
                        <NavLink to={`/projects/${slugProjectConnectedMember}`} exact className="nav-item nav-link text-center text-white text-uppercase font-weight-bold">
                          Mon projet
                        </NavLink>
                        <NavLink to={`/projects/${slugProjectConnectedMember}/edit`} exact className="nav-item nav-link text-center text-white text-uppercase font-weight-bold">
                          Modifier mon projet
                        </NavLink>
                      </div>
                    )}
                    <DropdownItem divider />
                  </DropdownMenu>
                </UncontrolledDropdown>
              )}
              <NavItem>
                {/* Si le membre est connecté "Me déconnecter" s'affiche, sinon "Me connecter" s'affiche */}
                {
                  (isConnected)
                    ? <ReactStrapLink className="btn btn-outline-white mx-3 btn-border-radius text-white text-uppercase font-weight-bold" onClick={() => this.disconnect()}>Me déconnecter</ReactStrapLink>
                    : <NavLink exact to="/login"><ReactStrapLink className="btn btn-outline-white mx-3 btn-border-radius text-white text-uppercase font-weight-bold" >Me connecter</ReactStrapLink></NavLink>
                }
              </NavItem>
            </Nav>
          </Collapse>
        </Navbar>
      </div>
    );
  }
}

ReactStrapNavbar.propTypes = {
  isConnected: PropTypes.bool.isRequired,
  page: PropTypes.string.isRequired,
  connectMember: PropTypes.func.isRequired,
  disconnectMember: PropTypes.func.isRequired,
  getConnectedMember: PropTypes.func.isRequired,
};

export default ReactStrapNavbar;
