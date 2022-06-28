describe('Magic link', () => {
  it('can login to access account settings', () => {

    cy.visit('/account/settings');

    cy.findByLabelText('Votre Email').type('gabriel@tentacode.test');
    cy.contains('Envoyer un lien magique').click();

    cy.contains('Votre lien magique vous attend !');

    cy.wait(1000); // @TODO pas top

    cy.maildevGetLastMessage().then((email) => {
      expect(email.subject).to.equal("Votre lien magique de connexion à ireadit 🔑");

      expect(email.to[0].name).equal('tentacode');
      expect(email.to[0].address).equal('gabriel@tentacode.test');

      cy.document().invoke('write', email.html)
      cy.findByText('Se connecter à ireadit').click();

      cy.findByText('Se déconnecter');
    });
  })

  it('fails if registration form is empty', () => {
    cy.visit('/magic-link')
    cy.contains('Envoyer un lien magique').click();

    cy.contains("L'email ne doit pas être vide.");
  })

  it('fails if registration email is invalid', () => {
    cy.visit('/magic-link')
    cy.findAllByLabelText('Votre Email').type('non@.');
    cy.contains('Envoyer un lien magique').click();

    cy.contains("Cette valeur n'est pas une adresse email valide.");
  })

  // todo -> email non présent
})