# -*- coding: utf-8 -*-

from odoo import models, fields, api, tools
from openerp.exceptions import except_orm, ValidationError
import datetime
import random

class jugador(models.Model):
    _inherit = 'res.partner'
    fecha_creacion = fields.Date(default=lambda self: datetime.date.today())
    fecha_muerte = fields.Date(default=lambda self: datetime.date.today() + datetime.timedelta(days=10))
    image = fields.Binary()
    name = fields.Char(string='Nombre jugador',
                       default=lambda self: self._get_default_name(), )
    ciutat = fields.One2many('game.ciutat', 'jugador')
    is_player = fields.Boolean(default=False)
    default_is_player = fields.Boolean(default=False)
    password = fields.Char()
    @api.constrains('ciutat')
    def _check_ciutat(self):
        print("PEPE")
        for record in self:
            if len(record.ciutat) > 2:
                raise ValidationError("No puedes tener mas de dos ciudades")

    @api.multi
    def eliminar_jugador(self):
        for j in self:
            for c in j.ciutat:
                for r in c.recursos:
                    r.unlink()
                for m in c.mines:
                    m.unlink()
                c.unlink()
            j.unlink()

    @api.model
    def _get_default_name(self):
        lista_nombres = ['Michael Burnham', 'Saru', 'Leonard Nimoy', 'DeForest Kelley', 'James Doohan',
                         'Nichelle Nichols', 'George Takei', 'Grace Lee Whitney', 'Majel Barrett', 'Walter Koenig',
                         'Beve', 'Zens', 'Locarth', 'Werian', 'Cinence', 'Reptep', 'Jupixave', 'Tempans']

        random_name = random.choice(lista_nombres)
        return random_name

    @api.model
    def create(self, values):
        res = super(jugador, self).create(values)
        names = ['Vulcano', 'Minshara', 'Khan', 'Voyager', 'Tau', 'Cyanga V', 'Defiant', 'Mudd', 'Elanna',
                 'Tholianos', 'Tholia', 'Medusanos', 'Amaurotam', 'Utopia', 'Ragnok', 'Vetusta', 'Castle Rock',
                 'Camplot']

        f = self.env['game.ciutat'].create({
            'name': str(random.choice(names)),
            'vida': 1000,
            'jugador': res.id,
        })

        return res

    # metodo que crea una ciudad nueva desde el boton
    @api.multi
    def crear_ciudad(self):
        names = ['Vulcano', 'Minshara', 'Khan', 'Voyager', 'Tau', 'Cyanga V', 'Defiant', 'Mudd', 'Elanna',
                 'Tholianos', 'Tholia', 'Medusanos', 'Amaurotam', 'Utopia', 'Ragnok', 'Vetusta', 'Castle Rock',
                 'Camplot']
        self.ciutat.create({

            'name': str(random.choice(names)),
            'vida': 1000,
            'jugador': self.id

        })

    @api.model
    def update_recursos(self):  # metodo para el cron

        ciutat_total = self.env['game.ciutat'].search([])
        print('\x1b[6;30;42m' + 'Actualizando recursos..' + '\x1b[0m')
        for c in ciutat_total:
            for r in c.recursos:
                for m in c.mines:
                    if m.mina.recurs.name == r.name:
                        if m.minutos == 0:
                            r.cantidad += m.produccion
                            m.mejora = False
                            m.write({'status': 'Mejorada'})
                        elif m.mejora:
                            m.write({'status': 'Mejorando...'})
                            m.write({'minutos': m.minutos - 1})
            if c.vida == 0:
                c.jugador = False

            if c.wars_ganadas != c.historic.num_wins:
                self.env['game.historic'].create({'ciutat': c.id, 'num_wins': c.wars_ganadas})

        print('\x1b[6;30;42m' + 'Recursos actualizados!' + '\x1b[0m')


class ciutat(models.Model):
    _name = 'game.ciutat'
    name = fields.Char(string='Nombre ciudades',
                       default=lambda self: self._get_default_name(), )
    image = fields.Binary()
    fecha_creacion = fields.Date(default=lambda self: datetime.date.today())

    # metodo para coger el nombre por defecto de la ciudad
    @api.model
    def _get_default_name(self):
        lista_nombres = ['Vulcano', 'Minshara', 'Khan', 'Voyager', 'Tau', 'Cyanga V', 'Defiant', 'Mudd', 'Elanna',
                         'Tholianos', 'Tholia', 'Medusanos', 'Amaurotam', 'Utopia', 'Ragnok', 'Vetusta', 'Castle Rock',
                         'Camplot']
        random_name = random.choice(lista_nombres)
        return random_name

    vida = fields.Float(default=1000)
    jugador = fields.Many2one('res.partner', ondelete="cascade")
    recursos = fields.One2many('game.recursos', 'ciutat', ondelete="cascade")
    mines = fields.One2many('game.mines', 'ciutat', ondelete="cascade")
    soldado = fields.One2many('game.soldado', 'ciutat', ondelete="cascade")
    naves = fields.One2many('game.naves', 'ciutat', ondelete="cascade")
    wars = fields.Many2many('game.wars')
    wars_ganadas = fields.Integer(default=0)
    historic = fields.One2many('game.historic', 'ciutat')
    ocultar_bool = fields.Boolean(default=False)

    @api.multi
    def refresh_pag(self):
        return {
            'type': 'ir.actions.client',
            'tag': 'reload',
        }

    # override a oncreate
    @api.multi
    def create(self, values):
        res = super(ciutat, self).create(values)
        array_recursos = ['game.cerio', 'game.holmio', 'game.lutecio', 'game.itrio']
        array_mines = ['game.central_fusion', 'game.arco_electrico', 'game.cavitacion', 'game.rayo_laser']
        array_soldats = ['game.soldado_raso', 'game.soldado_avanzado', 'game.teniente', 'game.piloto',
                         'game.comandante']

        array_naves = ['game.nave_basica', 'game.nave_de_asalto', 'game.nave_pesada']

        for i in array_recursos:
            r = res.recursos.create({
                'ciutat': res.id,
                'recurs': self.env.ref(i).id,
                'name': self.env.ref(i).name,
                'cantidad': 1000
            })

        for j in array_mines:
            m = res.mines.create({
                'ciutat': res.id,
                'mina': self.env.ref(j).id,
                'name': self.env.ref(j).name,
                'coste': 100
            })

        for k in array_soldats:
            q = res.soldado.create({
                'ciutat': res.id,

                'name': self.env.ref(k).name,
                'ataque': self.env.ref(k).ataque,
                'vida': self.env.ref(k).vida,
                'velocidad': self.env.ref(k).velocidad,
                'piloto': self.env.ref(k).piloto,
                'comandante': self.env.ref(k).comandante,
                'cantidad': self.env.ref(k).cantidad,
                'recurs': self.env.ref(k).recurs.id
            })

        for x in array_naves:
            n = res.naves.create({
                'ciutat': res.id,
                'name': self.env.ref(x).name,
                'ataque': self.env.ref(x).ataque,
                'vida': self.env.ref(x).vida,
                'velocidad': self.env.ref(x).velocidad,
                'cantidad': self.env.ref(x).cantidad,
                'recurs': self.env.ref(x).recurs.id,
                'cap_transporte': self.env.ref(x).cap_transporte

            })

        return res


class recursos(models.Model):
    _name = 'game.recursos'
    name = fields.Char()
    ciutat = fields.Many2one('game.ciutat')
    recurs = fields.Many2one('game.recurs')
    cantidad = fields.Float()

    # calculo del coste para upgradear una mina
    @api.multi
    def calcular_coste(self, recurs1, nivel, produccion, name_mina):
        for r in self:
            if nivel > 0:
                coste = (nivel + 2) * ((produccion + 20) * 10)
            else:
                coste = 100

            if r.name == recurs1.name:
                if r.cantidad >= coste:
                    for m in r.ciutat.mines:
                        if m.name == name_mina:
                            m.mejora = True
                            if m.minutos == 0:
                                m.nivel += 1
                                m.produccion += (nivel + 1) * 100
                                m.minutos = (2 * (nivel + 1))
                                m.tiempo_total = (2 * (nivel + 1))
                                m.coste = coste
                                r.cantidad -= coste

                else:
                    raise except_orm('ERROR',
                                     'Faltan recursos para hacer la mejora')


class recurs(models.Model):
    _name = 'game.recurs'
    name = fields.Char()
    image = fields.Binary()
    mina = fields.One2many('game.mina', 'recurs')
    ciutat = fields.Many2one('game.ciutat')
    soldado = fields.One2many('game.soldado', 'recurs')
    naves = fields.One2many('game.naves', 'recurs')


class mines(models.Model):
    _name = 'game.mines'
    name = fields.Char()
    ciutat = fields.Many2one('game.ciutat')
    mina = fields.Many2one('game.mina')
    coste = fields.Float()
    produccion = fields.Float()
    nivel = fields.Integer()
    minutos = fields.Integer()
    mejora = fields.Boolean(default=False)
    status = fields.Char()
    const_percent = fields.Float(compute='_get_const_percent')
    tiempo_total = fields.Float()

    # metodo que llama a la funcion de calcular cantidad
    @api.multi
    def llamar_calc_cantidad(self):
        if self.id=="":
            return False
        llista_mines = self.env['game.mines'].search([])
        id_mina = int(self.id)
        
        for lm in llista_mines:
            if lm.id == id_mina:
                lm.ciutat.recursos.calcular_coste(lm.mina.recurs, lm.nivel, lm.minutos, lm.name)
                return True
        return False


    @api.depends('minutos')
    def _get_const_percent(self):
        for r in self:
            if r.minutos > 0:
                r.const_percent = 100 - (r.minutos / r.tiempo_total) * 100


class mina(models.Model):
    _name = 'game.mina'
    name = fields.Char()
    image = fields.Binary()
    recurs = fields.Many2one('game.recurs')


class soldado(models.Model):
    _name = 'game.soldado'
    name = fields.Char()
    ciutat = fields.Many2one('game.ciutat')
    ataque = fields.Float()
    vida = fields.Float()
    velocidad = fields.Float()
    piloto = fields.Boolean()
    comandante = fields.Boolean()
    recurs = fields.Many2one('game.recurs')
    cantidad = fields.Integer()
    cant_tropas = fields.Integer(default=0)

    @api.multi
    def comprar_soldado(self):
        if self.id=="":
            return False
        llista_soldados = self.env['game.soldado'].search([])
        id_soldado = int(self.id)
        for ls in llista_soldados:
            if ls.id == id_soldado:
                for c in ls.ciutat:
                    for rs in c.recursos:
                        for r in rs.recurs:
                            if r.id == ls.recurs.id:
                                if rs.cantidad >= ls.cantidad:
                                    rs.cantidad -= ls.cantidad
                                    ls.cant_tropas += 1
                                else:
                                    raise except_orm('ERROR',
                                            'No tienes suficientes recursos para comprar al soldado')
        


class naves(models.Model):
    _name = 'game.naves'
    name = fields.Char()
    ciutat = fields.Many2one('game.ciutat')
    ataque = fields.Float()
    cap_transporte = fields.Integer()
    vida = fields.Float()
    velocidad = fields.Integer()
    recurs = fields.Many2one('game.recurs')
    cantidad = fields.Integer()
    cant_tropas = fields.Integer(default=0)

    @api.multi
    def comprar_naves(self):
        for c in self.ciutat:
            for rs in c.recursos:
                for r in rs.recurs:
                    if r.id == self.recurs.id:
                        if rs.cantidad >= self.cantidad:
                            rs.cantidad -= self.cantidad
                            self.cant_tropas += 1
                        else:
                            raise except_orm('ERROR',
                                             'No tienes suficientes recursos para comprar la nave')


class historic(models.Model):
    _name = 'game.historic'
    ciutat = fields.Many2one('game.ciutat')
    fecha_actual = fields.Date(default=lambda self: datetime.date.today())
    num_wins = fields.Integer()


class wizard(models.TransientModel):
    _name = 'game.wizard'

    def default_atacante(self):
        return self.env['game.ciutat'].browse(self._context.get('active_id'))

    def default_jugador(self):
        atacante = self.default_atacante()
        ciutats = self.env['game.ciutat'].search([])
        for c in ciutats:
            if atacante.id == c.id:
                return c.jugador.id

    jugador = fields.Many2one('res.partner', default=default_jugador)

    atacante = fields.Many2many('game.ciutat', default=default_atacante, relation='atacante_tab',
                                domain="[('id','not in', defensor), ('jugador','=', jugador)]")

    defensor = fields.Many2many('game.ciutat', relation='defensor_tab',
                                domain="[('id','not in', atacante), ('jugador','!=', jugador)]")

    @api.multi
    def launch(self):
        self.env['game.wars'].create({'jugador': self.atacante.jugador.id,
                                      'atacante': [(6, 0, self.atacante.ids)],
                                      'defensor': [(6, 0, self.defensor.ids)]
                                      })


class wars(models.Model):
    _name = 'game.wars'
    jugador = fields.Many2one('res.partner', domain="[('is_player', '!=', False)]")

    atacante = fields.Many2many('game.ciutat', relation='atacante',
                                domain="[('id','not in', defensor), ('jugador','=', jugador)]")
    defensor = fields.Many2many('game.ciutat', relation='defensor',
                                domain="[('id','not in', atacante), ('jugador','!=', jugador)]")

    empezar = fields.Boolean(default=False)
    minutos = fields.Integer(default=30)
    state = fields.Char(default="Pendiente a empezar")
    acabada = fields.Boolean(default=False)
    const_percent = fields.Float(compute='_get_const_percent')

    @api.depends('minutos')
    def _get_const_percent(self):
        for r in self:
            if r.minutos > 0:
                r.const_percent = 100 - (r.minutos / 30) * 100

    @api.multi
    def ataque(self):
        cant_total_nav = 0
        cant_total_sold = 0

        for a in self.atacante:
            for cn in a.naves:
                cant_total_nav += cn.cant_tropas

            for cs in a.soldado:
                cant_total_sold += cs.cant_tropas

        if cant_total_nav == 0 and cant_total_sold == 0:
            raise except_orm('ERROR',
                             'No tienes ninguna tropa para poder atacar')
        else:
            self.empezar = True

    @api.model
    def start_war(self):

        wars_total = self.env['game.wars'].search([])
        for w in wars_total:
            for st in w:
                if st.empezar:
                    st.state = "Guerra empezada las tropas estan en marcha!"
                    if st.minutos == 0 and not st.acabada:
                        at_vel_total_tropas = 0
                        at_at_total_tropas = 0
                        at_vid_total_tropas = 0

                        def_vel_total_tropas = 0
                        def_at_total_tropas = 0
                        def_vid_total_tropas = 0

                        for a in st.atacante:

                            for cn in a.naves:
                                at_vel_total_tropas += cn.velocidad
                                at_at_total_tropas += cn.ataque
                                at_vid_total_tropas += cn.vida
                            for cs in a.soldado:
                                at_vel_total_tropas += cs.velocidad
                                at_at_total_tropas += cs.ataque
                                at_vid_total_tropas += cs.vida
                        total_atacante = at_vel_total_tropas + at_at_total_tropas + at_vid_total_tropas
                        if len(st.atacante) > 0:
                            total_atacante /= len(st.atacante)

                        for d in st.defensor:

                            for acn in d.naves:
                                def_vel_total_tropas += acn.velocidad * acn.cant_tropas
                                def_at_total_tropas += acn.ataque * acn.cant_tropas
                                def_vid_total_tropas += acn.vida * acn.cant_tropas

                            for dcs in d.soldado:
                                def_vel_total_tropas += dcs.velocidad * dcs.cant_tropas
                                def_at_total_tropas += dcs.ataque * dcs.cant_tropas
                                def_vid_total_tropas += (dcs.vida * dcs.cant_tropas) + d.vida
                        long_ciutat = len(st.defensor)
                        total_defensor = (
                                def_at_total_tropas + def_vid_total_tropas + def_vel_total_tropas)

                        if long_ciutat > 0:
                            total_defensor /= long_ciutat

                        total = total_atacante + total_defensor
                        percent_ataq = (total_atacante * 90) / total
                        percent_def = (total_defensor * 100) / total

                        if percent_def < 5:
                            percent_def = 10

                        if percent_ataq > 90:
                            percent_ataq = 90

                        if percent_def <= random.randrange(0, 100):

                            cont = 0
                            for s in st.defensor:
                                if cont == 0:
                                    s.vida = 0
                                    s.jugador = False
                                else:
                                    s.vida -= percent_ataq

                            for a in st.atacante:
                                a.wars_ganadas += 1
                                for r in a.recursos:
                                    r.cantidad += percent_def * 10
                        else:
                            for a in st.atacante:
                                for s in a.soldado:
                                    s.cant_tropas = 0
                                for n in a.naves:
                                    n.cant_tropas = 0
                            for d in st.defensor:
                                d.wars_ganadas += 1

                        st.minutos -= 1

                    elif st.minutos >= 0:
                        st.minutos -= 1

                    else:
                        st.acabada = True
                        st.empezar = False
                        st.acabada = "Guerra terminada"
