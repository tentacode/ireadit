describe('Landing', () => {
  it('contains a valid title', () => {
    cy.visit('/')
    cy.contains('Ne laissez plus passer votre veille technique !')
  })
})