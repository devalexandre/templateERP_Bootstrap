<?php
/**
 * LoginForm Registration
 * @author  <your name here>
 */
class LoginForm extends TPage
{
    protected $form; // form
    protected $notebook;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $table = new TTable;
        $table->width = '100%';
        
        // creates the form
        $this->form = new TForm('form_login');
        $this->form->class = 'tform';
        $this->form->style = 'margin:auto;width: 350px';
        
        // add the notebook inside the form
        $this->form->add($table);
        
        // create the form fields
        $login      = new TEntry('login');
        $password   = new TPassword('password');

        // define the sizes
        $login->setSize(150, 40);
        $password->setSize(150, 40);
        
        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onLogin')), _t('Login'));
        $save_button->setImage('ico_apply.png');
        
        // add a row for the field login
        $row=$table->addRow();
        $cell = $row->addCell(new TLabel('Login'));
        $cell->colspan = 2;
        $row->class = 'tformtitle';
        
        $table->addRowSet(new TLabel(_t('User') . ': '), $login);
        $table->addRowSet(new TLabel(_t('Password') . ': '),$password);
        $row = $table->addRowSet($save_button, '');
        $row->class = 'tformaction';

        $this->form->setFields(array($login,$password,$save_button));
        
        // add the form to the page
        parent::add($this->form);
    }

    /**
     * Autenticates the User
     */
    function onLogin()
    {
        try
        {
            TTransaction::open('permission');
            $data = $this->form->getData('StdClass');
            $this->form->validate();
            $user = SystemUser::autenticate( $data->login, $data->password );
            if ($user)
            {
                $programs = $user->getPrograms();
                $programs['LoginForm'] = TRUE;
                
                TSession::setValue('logged', TRUE);
                TSession::setValue('login', $data->login);
                TSession::setValue('username', $user->name);
                TSession::setValue('frontpage', '');
                TSession::setValue('programs',$programs);
                
                $frontpage = $user->frontpage;
                
                if ($frontpage instanceof SystemProgram AND $frontpage->controller)
                {
                    TApplication::gotoPage($frontpage->controller); // reload
                    TSession::setValue('frontpage', $frontpage->controller);
                }
                else
                {
                    TApplication::gotoPage('EmptyPage'); // reload
                    TSession::setValue('frontpage', 'EmptyPage');
                }
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TSession::setValue('logged', FALSE);
            TTransaction::rollback();
        }
    }
    
    /**
     * Logout
     */
    function onLogout()
    {
        TSession::freeSession();
        TApplication::gotoPage('LoginForm', '');
    }
}
?>