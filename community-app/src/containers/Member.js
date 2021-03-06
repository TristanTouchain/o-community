// npm import
import { connect } from 'react-redux';

// local import
import Member from 'src/components/Member';

// action creators
import { getMember } from 'src/store/actions/membersActions';

// == state ==
const mapStateToProps = state => ({
  member: state.member,
  memberID: state.memberID,
  page: state.actualPage,
  previousSlug: state.slug,
});

// == dispacth ==
const mapDispatchToProps = dispatch => ({
  getMemberWithId: (id) => {
    dispatch(getMember(id));
  },
});


// Container
const MemberContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Member);

// Export
export default MemberContainer;
