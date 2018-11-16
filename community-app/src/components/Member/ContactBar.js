/**
 * NPM import
 */
import React from 'react';
import {
  FaRegEnvelope,
  FaPhone,
  FaGithub,
  FaLinkedinIn,
  FaDesktop,
  FaMapMarkerAlt,
} from 'react-icons/fa';

/**
 * Local import
 */
// Components
import ArrowDown from 'src/components/ArrowDown';

// Styles
import './member.scss';

/**
 * Code
 */
const ContactBar = () => (
  <div id="member-info-contactbar" className="d-flex justify-content-between">
    <div>
      <a href=""><FaRegEnvelope className="text-white" /></a>
      <a href=""><FaPhone className="text-white" /></a>
      <a href=""><FaGithub className="text-white" /></a>
      <a href=""><FaLinkedinIn className="text-white" /></a>
      <a href=""><FaDesktop className="text-white" /></a>
    </div>
    <ArrowDown />
    <div className="d-flex">
      <FaMapMarkerAlt className="text-white" />
      <span id="member-info-contactbar-localisation" className="text-white">Nantes, France</span>
    </div>
  </div>

);

/**
 * Export
 */
export default ContactBar;