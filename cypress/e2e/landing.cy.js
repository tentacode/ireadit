describe('Landing', () => {
  it('contains a valid title', () => {
    cy.visit('/')
    cy.contains('Ne ratez plus votre veille technique !')
  })
})