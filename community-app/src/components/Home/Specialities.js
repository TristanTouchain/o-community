/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';

/**
 * Local import
 */
// Components

// Styles
import './home.scss';

/**
 * Code
 */
const Specialities = ({ getMembersSpe }) => (
  <section id="home-specialities" className="d-flex flex-column justify-content-center align-items-center full-height bg-home-darker">
    <h2 id="home-specialities-title">Les spécialités</h2>
    <h3 id="home-specialities-subtitle">Consultez les profils de nos étudiants selon leur spécialité</h3>
    <div id="home-specialities-list" className="d-flex justify-content-around flex-wrap col-9 col-md-9 col-xl-6">
      <Link activeClassName="" exact to="/members" onClick={() => getMembersSpe('wordpress')} className="d-flex flex-column col-12 col-md-4 mt-7 mt-md-0">
        <img src="src/images/OClockCommunity-LogoWordPress.svg" alt="" />
        <h4>WordPress</h4>
        <button type="button" className="btn btn-outline-white mx-3 btn-border-radius text-uppercase mt-2">Consulter</button>
      </Link>
      <Link activeClassName="" exact to="/members" onClick={() => getMembersSpe('react')} className="d-flex flex-column col-12 col-md-4 mt-7 mt-md-0">
        <img src="src/images/OClockCommunity-LogoReact.svg" alt="" />
        <h4>React</h4>
        <button type="button" className="btn btn-outline-white mx-3 btn-border-radius text-uppercase mt-2">Consulter</button>
      </Link>
      <Link activeClassName="" exact to="/members" onClick={() => getMembersSpe('symfony')} className="d-flex flex-column col-12 col-md-4 mt-7 mt-md-0">
        <img src="src/images/OClockCommunity-LogoSymfony.svg" alt="" />
        <h4>Symfony</h4>
        <button type="button" className="btn btn-outline-white mx-3 btn-border-radius text-uppercase mt-2">Consulter</button>
      </Link>
    </div>
  </section>
);

Specialities.propTypes = {
  getMembersSpe: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Specialities;
