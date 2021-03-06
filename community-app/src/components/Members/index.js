/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import ReactLoading from 'react-loading';
/**
 * Local import
 */
import SadFace from 'src/images/sad-cry-regular.png';

// Components
import SelectInput from '../../containers/SelectInput';
import TextInput from '../../containers/TextInput';
import ArrowDown from '../ArrowDown';
import SingleMember from '../SingleMember';

// Styles
import './members.scss';


/**
 * Code
 */
class Members extends React.Component {
  componentDidMount() {
    const { getMembers, getFilters } = this.props;
    getMembers();
    getFilters();
  }

  render() {
    const {
      listSpe,
      listPromo,
      listStatus,
      filterSpeMembers,
      filterPromoMembers,
      filterStatusMembers,
      filterTextMembers,
    } = this.props;
    let { listMembers } = this.props;
    // Mise en place des filtres
    if (filterSpeMembers !== '' && listMembers !== null) {
      listMembers = listMembers.filter((member) => {
        if (member.specialisation !== null) {
          return (member.specialisation.name === `${filterSpeMembers}`);
        }
        return false;
      });
    }
    if (filterPromoMembers !== '' && listMembers !== null) {
      listMembers = listMembers.filter(member => member.promotion.name === `${filterPromoMembers}`);
    }
    if (filterStatusMembers !== '' && listMembers !== null) {
      listMembers = listMembers.filter((member) => {
        if (member.professionalStatus !== null) {
          return (member.professionalStatus.name === `${filterStatusMembers}`);
        }
        return false;
      });
    }
    if (filterTextMembers !== '' && listMembers !== null) {
      listMembers = listMembers.filter(member => (
        member.firstname.toLowerCase().includes(filterTextMembers.toLowerCase()) || member.lastname.toLowerCase().includes(filterTextMembers.toLowerCase())
      ));
    }
    return (
      <div id="members">
        <section id="members-presentation" className="d-flex flex-column justify-content-center align-items-center bg-h-100vh bg-members row">
          <h1>Étudiants</h1>
          <h3>Vous êtes prêts ? Eux oui !</h3>
          { (Object.keys(listSpe).length > 0) && (Object.keys(listPromo).length > 0) && (Object.keys(listStatus).length > 0)
            ? (
              <div id="members-form" className="row justify-content-center">
                <SelectInput type="Spécialisation" list={listSpe} page="Members" value={filterSpeMembers} />
                <SelectInput type="Promotion" list={listPromo} page="Members" value={filterPromoMembers} />
                <SelectInput type="Status Professionnel" list={listStatus} page="Members" value={filterStatusMembers} />
                <TextInput type="filterTextMembers" placeholder="Prénom Nom" value={filterTextMembers} />
              </div>
            )
            : (
              <section id="members-form" className="justify-content-center align-items-center text-center d-flex flex-column">
                <ReactLoading type="bubbles" color="#fdf1cd" height={100} width={100} />
                <p className="singlemember-name ">Chargement ...</p>
              </section>
            )}
          <ArrowDown />
        </section>
        <section id="members-list" className="bg-members-darker justify-content-center row">
          {listMembers.map(member => (
            <SingleMember
              key={member.slug}
              firstname={member.firstname}
              lastname={member.lastname}
              promotion={member.promotion !== null ? member.promotion.name : ''}
              specialisation={member.specialisation !== null ? member.specialisation.name : ''}
              contentUrl={member.contentUrl}
              slug={member.slug}
            />))}
          {(listMembers.length === 0) && (
            <div id="members-list-noresult" className="text-center">
              <img src={SadFace} alt="sad face" />
              <h1>Oh non ! Personne ne correspond...</h1>
              <h1>Essaye encore !</h1>
            </div>
          )}
        </section>
      </div>
    );
  }
}

Members.propTypes = {
  listMembers: PropTypes.array.isRequired,
  getMembers: PropTypes.func.isRequired,
  getFilters: PropTypes.func.isRequired,
  listSpe: PropTypes.array.isRequired,
  listPromo: PropTypes.array.isRequired,
  listStatus: PropTypes.array.isRequired,
  filterSpeMembers: PropTypes.string.isRequired,
  filterPromoMembers: PropTypes.string.isRequired,
  filterStatusMembers: PropTypes.string.isRequired,
  filterTextMembers: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Members;
