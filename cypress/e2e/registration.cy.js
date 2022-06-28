describe('Registration', () => {
    it('register from the landing page', () => {
        cy.visit('/')
        cy.contains("S'inscrire à la beta").click()
  
        cy.findAllByLabelText('Votre Email').type('john.doe@example.com');
        cy.findAllByLabelText("Votre nom d'utilisateur").type('jojo');
  
        cy.findByText("S'inscrire").click();
  
        cy.contains('Votre inscription est presque terminée !');
  
        cy.wait(1000); // @TODO pas top
  
        cy.maildevGetLastMessage().then((email) => {
          expect(email.to[0].name).equal('jojo');
          expect(email.to[0].address).equal('john.doe@example.com');
          expect(email.subject).to.equal("À un clic près de rejoindre ireadit ! 🚀");
  
          cy.document().invoke('write', email.html)
          cy.findByText('Valider mon compte').click();

          cy.findByText('Se déconnecter');
        });
    })

    it('fails if registration form is empty', () => {
      cy.visit('/register')

      cy.findByText("S'inscrire").click()

      cy.contains("L'email ne doit pas être vide.");
      cy.contains("Le nom d'utilisateur ne doit pas être vide.");
    })

    it('fails if registration email is invalid', () => {
      cy.visit('/register')

      cy.findAllByLabelText('Votre Email').type('non@.');
      cy.findAllByLabelText("Votre nom d'utilisateur").type('jojo');

      cy.findByText("S'inscrire").click()
      cy.contains("Cette valeur n'est pas une adresse email valide.");
    })

    it('fails if registration username is too short', () => {
      cy.visit('/register')

      cy.findAllByLabelText('Votre Email').type('oui@oui.com');
      cy.findAllByLabelText("Votre nom d'utilisateur").type('jo');

      cy.findByText("S'inscrire").click()
      cy.contains("Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.");
    })

    it('fails if registration username is invalid', () => {
      cy.visit('/register')

      cy.findAllByLabelText('Votre Email').type('oui@oui.com');
      cy.findAllByLabelText("Votre nom d'utilisateur").type('bloup/');

      cy.findByText("S'inscrire").click()
      cy.contains("Le nom d'utilisateur ne doit contenir que des lettres, des chiffres et des tirets.");
    })

    it('fails if registration email already exists', () => {
      cy.visit('/register')

      cy.findAllByLabelText('Votre Email').type('gabriel@tentacode.test');
      cy.findAllByLabelText("Votre nom d'utilisateur").type('toto');

      cy.findByText("S'inscrire").click()
      cy.contains("Cet email est déjà utilisé.");
    })

    it('fails if registration username already exists', () => {
      cy.visit('/register')

      cy.findAllByLabelText('Votre Email').type('oui@oui.com');
      cy.findAllByLabelText("Votre nom d'utilisateur").type('tentacode');

      cy.findByText("S'inscrire").click()
      cy.contains("Ce nom d'utilisateur est déjà utilisé.");
    })
  })