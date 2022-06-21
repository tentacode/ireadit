describe('Landing spec', () => {
  it('contains a valid title', () => {
    cy.visit('/')
    cy.contains('Ireadit 📚✅')
  })
})