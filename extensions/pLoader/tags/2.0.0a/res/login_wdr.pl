#-----------------------------------------------------------------------------
# Perl source generated by wxDesigner from file: login.wdr
# Do not modify this file, all changes will be lost!
#-----------------------------------------------------------------------------

use Wx;
use strict;

use Wx qw( wxDefaultSize wxDefaultPosition wxID_OK wxID_SAVE wxID_SAVEAS wxID_CANCEL wxID_YES wxID_EXIT wxID_ABOUT wxID_HELP );
use Wx qw( wxVERTICAL wxHORIZONTAL wxALL wxLEFT wxRIGHT wxTOP wxBOTTOM wxCENTRE wxGROW wxADJUST_MINSIZE );
use Wx qw( wxALIGN_RIGHT wxALIGN_BOTTOM wxALIGN_CENTER wxALIGN_CENTER_VERTICAL wxALIGN_CENTER_HORIZONTAL );
use Wx qw( wxTE_PASSWORD );

# Bitmap functions

use Wx qw( wxNullBitmap wxBITMAP_TYPE_PNG );

# Window functions

use vars qw($ID_TEXT); $ID_TEXT = 10000;
use vars qw($ID_PWG_URL); $ID_PWG_URL = 10001;
use vars qw($ID_PWG_USERNAME); $ID_PWG_USERNAME = 10002;
use vars qw($ID_PWG_PASSWORD); $ID_PWG_PASSWORD = 10003;
use vars qw($ID_PWG_OK); $ID_PWG_OK = 10004;
use vars qw($ID_PWG_CANCEL); $ID_PWG_CANCEL = 10005;

sub Login {
    my( $item0 ) = Wx::BoxSizer->new( wxVERTICAL );
    
    $item0->AddSpace( 20, 20, 0, wxALIGN_CENTER|wxALL, 5 );

    my( $item2 ) = Wx::StaticBox->new( $_[0], -1, "" );
    my( $item1 ) = Wx::StaticBoxSizer->new( $item2, wxVERTICAL );
    
    my( $item3 ) = Wx::FlexGridSizer->new( 0, 2, 0, 0 );
    
    my( $item4 ) = Wx::StaticText->new( $_[0], $main::ID_TEXT, "Piwigo url", wxDefaultPosition, wxDefaultSize, 0 );
    $item3->AddWindow( $item4, 0, wxALIGN_CENTER_VERTICAL|wxALL, 5 );

    my( $item5 ) = Wx::TextCtrl->new( $_[0], $main::ID_PWG_URL, "", wxDefaultPosition, [400,-1], 0 );
    $item3->AddWindow( $item5, 0, wxALIGN_CENTER|wxALL, 5 );

    my( $item6 ) = Wx::StaticText->new( $_[0], $main::ID_TEXT, "Admin. username :", wxDefaultPosition, wxDefaultSize, 0 );
    $item3->AddWindow( $item6, 0, wxALIGN_CENTER_VERTICAL|wxALL, 5 );

    my( $item7 ) = Wx::TextCtrl->new( $_[0], $main::ID_PWG_USERNAME, "", wxDefaultPosition, [200,-1], 0 );
    $item3->AddWindow( $item7, 0, wxALIGN_CENTER_VERTICAL|wxALL, 5 );

    my( $item8 ) = Wx::StaticText->new( $_[0], $main::ID_TEXT, "Admin. password :", wxDefaultPosition, wxDefaultSize, 0 );
    $item3->AddWindow( $item8, 0, wxALIGN_CENTER_VERTICAL|wxALL, 5 );

    my( $item9 ) = Wx::TextCtrl->new( $_[0], $main::ID_PWG_PASSWORD, "", wxDefaultPosition, [200,-1], wxTE_PASSWORD );
    $item3->AddWindow( $item9, 0, wxALIGN_CENTER_VERTICAL|wxALL, 5 );

    $item1->Add( $item3, 0, wxALIGN_CENTER|wxALL, 5 );

    $item0->Add( $item1, 0, wxALIGN_CENTER|wxALL, 5 );

    $item0->AddSpace( 20, 30, 0, wxALIGN_CENTER|wxALL, 5 );

    my( $item10 ) = Wx::BoxSizer->new( wxHORIZONTAL );
    
    my( $item11 ) = Wx::Button->new( $_[0], $main::ID_PWG_OK, "OK", wxDefaultPosition, wxDefaultSize, 0 );
    $item11->SetDefault();
    $item10->AddWindow( $item11, 0, wxALIGN_CENTER|wxALL, 5 );

    my( $item12 ) = Wx::Button->new( $_[0], $main::ID_PWG_CANCEL, "Cancel", wxDefaultPosition, wxDefaultSize, 0 );
    $item10->AddWindow( $item12, 0, wxALIGN_CENTER|wxALL, 5 );

    $item0->Add( $item10, 0, wxALIGN_CENTER|wxALL, 5 );

    my( $set_size ) = @_ >= 3 ? $_[2] : 1;
    my( $call_fit ) = @_ >= 2 ? $_[1] : 1;
    if( $set_size == 1 ) {
         $_[0]->SetSizer( $item0 );
         
         if( $call_fit == 1 ) {
             $item0->SetSizeHints( $_[0] );
         }
    }
    
    $item0;
}

# Menu bar functions


# Toolbar functions


# End of generated file